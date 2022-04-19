<?php 
    require_once(realpath($_SERVER['DOCUMENT_ROOT']).'/PHP/happy_neighbor/model/Connection.php');

    class UserCRUD {

        private const NOT_NULL_PARAMETERS = [
            'name_user',
            'mail',
            'pass_user'
        ];

        public function __construct() {}

        /**
         * Insert a user in the table and return the result
         * 
         * @param object user object
         * @return bool if the user is inserted or not
         */
        public static function insert(User $user):bool {
            $result = false;
            $db = Db::connect();
            $isPossibleInsertUser = true;
            $attributes = $user -> getAttributes();
            foreach (self::NOT_NULL_PARAMETERS as $parameters) {
                if ($attributes[$parameters] == null || $attributes[$parameters] == "") {
                    $isPossibleInsertUser = false;
                    break;
                }
            }
            if ($isPossibleInsertUser) {
                $insertSentence = $db -> prepare("INSERT INTO user VALUES(null,'" . $attributes['name_user'] . "','" . $attributes['last_name'] . "','" . $attributes['mail'] . "','" . password_hash($attributes['pass_user'], PASSWORD_BCRYPT) ."','" . $attributes['profile_picture'] . "','" . $attributes['biography'] . "');");
                $result = $insertSentence->execute();
            }
            return $result;
        }

        /**
         * Update a user in the table and return the result
         * 
         * @param object user object
         * @return bool if the user is updated or not
         */
        public static function update(User $user): bool {
            $result = false;
            $db = Db::connect();
            $attributes = $user -> getAttributes();
            $idUser = $attributes['id'];
            unset($attributes['id']);
            if ( self::select($idUser) ) {
                $sentenceUpdate = "UPDATE user SET ";
                foreach ($attributes as $index => $val) {
                    if ($val != "" || $val != null) {
                        if (gettype($val) == "string") {
                            if ($index == "pass_user") {
                                $sentenceUpdate .= $index . "='" . password_hash($val,PASSWORD_BCRYPT) . "',";
                            } else {
                                $sentenceUpdate .= $index . "='" . $val . "',";
                            }
                        } else {
                            $sentenceUpdate .= $index . "=" . $val . ",";
                        }
                    }
                }
                $sentenceUpdate = substr($sentenceUpdate,0,-1);
                $sentenceUpdate .= " WHERE id=" . $idUser . ";";
                $updateSentence = $db -> prepare($sentenceUpdate);
                $result = $updateSentence->execute();
            }
            return $result;
        }

        /**
         * Remove a user in the table and return the result
         * 
         * @param int $id user id (Primary key)
         * @return bool if the user is removed or not
         */
        public static function remove(int $id): bool {
            $result = false;            
            $isPossibleRemoveUser = false;
            $communitiesToRemove = [];
            $db = Db::connect();
            if ( self::select( $id ) && $id > 0) {
                [$isPossibleRemoveUser, $communitiesToRemove] = self::validateRemoveUserCommunity(self::selectUserCommunities($id));
                if ($isPossibleRemoveUser) {
                    $insertSentence = $db -> prepare("DELETE FROM user WHERE id=$id");
                    $result = $insertSentence->execute();
                    if ($result) {
                        foreach ($communitiesToRemove as $idCommunity) {
                            $db -> query("DELETE FROM community WHERE id=$idCommunity");
                        }
                    }
                }
            }
            return $result;
        }

        /**
         * Search a user in the table and return it if found
         * 
         * @param int $id user id (Primary key)
         * @return mixed object UserPassHash or false if not located 
         */
        public static function select(int $id) {
            $user = false;
            $db = Db::connect();
            $result = $db -> query("SELECT * FROM user WHERE id=$id");
            if ($arrayUser = $result -> fetch(PDO::FETCH_ASSOC)) {
                $user = UserPassHash::getUser($arrayUser);
            }
            return $user;
        }

        /**
         * Search a user by email, validate the password and return a session token
         * 
         * @param string $mail user mail (Unique in table)
         * @param string $passUser password to validate against the hash
         * @return mixed new user session token in db or false
         */
        public static function login(string $mail, string $passUser) {
            $db = Db::connect();
            $tokenToSend = false;
            $resultSelect = $db -> query("SELECT id,pass_user FROM user WHERE mail='$mail'");
            $arrayPass = $resultSelect -> fetch(PDO::FETCH_ASSOC);
            $isPassCorrect = password_verify($passUser, $arrayPass['pass_user']);
            if ($isPassCorrect) {
                $newToken = self::generateToken($mail);
                $dropSentence = $db -> prepare("DELETE FROM session_token WHERE id_user=" . $arrayPass['id'] . ";");
                $isDroped = $dropSentence->execute();
                if ($isDroped) {
                    $insertSentence = $db -> prepare("INSERT INTO session_token VALUES ('$newToken',null," . $arrayPass['id'] . ");");
                    $isInserted = $insertSentence->execute();
                    if ($isInserted) {
                        $tokenToSend = $newToken;
                    }
                }
            }
            return $tokenToSend;
        }

        /**
         * Generate a user token session
         * 
         * @param string $mail user mail
         * @param int $lengthToken length of the token to generate, <10
         * @return string new user session token
         */
        private static function generateToken(string $mail, int $lengthToken = 15) {
            if ($lengthToken < 10) {
                $lengthToken = 10;
            }
            $date = new DateTime();
            return bin2hex(random_bytes(($lengthToken - ($lengthToken % 2)) / 2)) . "-" . $mail;
        }

        /**
         * Select the communities of a user
         * 
         * @param int $idUser user id
         * @return array communities of the user
         */
        public static function selectUserCommunities(int $idUser) {
            $communities = [];
            $db = Db::connect();
            $communitiesUser = $db -> query("SELECT id_community, is_admin FROM user_community WHERE id_user=$idUser;");
            while ($community = $communitiesUser -> fetch(PDO::FETCH_ASSOC)) {
                $communities[] = [$community['id_community'],$community['is_admin']];
            }
            return $communities;
        }

        /**
         * Validate if is possible remove the user rows in the user-community table
         * 
         * @param array $communities in which the user is
         * In every position of the array, you have a another array with this elements:
         *      0: id_community
         *      1: is_admin
         * @return array with two element:
         *      bool $isPossibleRemoveUser
         *      array $communitiesToRemove 
         */
        private function validateRemoveUserCommunity(array $communities) {
            $db = Db::connect();
            $isPossibleRemoveUser = true;
            $communitiesToRemove = [];
            foreach ($communities as $arrayCommunity) {
                $totalUser = 0;
                /**
                 * @var int $totalAdmins
                 * If the value of this var is equal to:
                 *      0: The user to remove is not a admin, remove the user.
                 *      1: The user to remove is the unique admin, not remove the user.
                 *      >1: The user to remove is not the unique admin, remove the user
                 */
                $totalAdmins = 0;
                $usersCommunity = $db -> query("SELECT id_user, is_admin FROM user_community WHERE id_community=$arrayCommunity[0]");
                while ($user = $usersCommunity -> fetch(PDO::FETCH_ASSOC)) {
                    $totalUser += 1;
                    if ($arrayCommunity[1] == 1 && $user['is_admin'] == 1) {
                        $totalAdmins += 1;
                    }
                }
                if ($totalUser <= 1) {
                    $communitiesToRemove[] = $arrayCommunity[0];
                } elseif ($totalAdmins == 1) {
                    $isPossibleRemoveUser = false;
                }
            }
            return [$isPossibleRemoveUser, $communitiesToRemove];
        }
    }
?>