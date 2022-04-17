<?php 
    require_once(realpath($_SERVER['DOCUMENT_ROOT']).'/PHP/happy_neighbor/model/Connection.php');

    class UserCRUD {

        public function __construct() {}

        /**
         * Insert a user in the table and return the result
         * 
         * @param object user object
         * @return bool if the user is inserted or not
         */
        public static function insert(User $user):bool {
            $db = Db::connect();
            $attributes = $user -> getAttributes();
            $insertSentence = $db -> prepare("INSERT INTO user VALUES(null,'" . $attributes['name_user'] . "','" . $attributes['last_name'] . "','" . $attributes['mail'] . "','" . $attributes['pass_user'] ."','" . $attributes['profile_picture'] . "','" . $attributes['biography'] . "');");
            return $insertSentence->execute();
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
            if ( self::select($attributes['id']) ) {
                $insertSentence = $db -> prepare("UPDATE user SET name_user='" . $attributes['name_user'] . "',last_name='" . $attributes['last_name'] . "',mail='" . $attributes['mail'] . "',pass_user='" . $attributes['pass_user'] ."',profile_picture='" . $attributes['profile_picture'] . "',biography='" . $attributes['biography'] . "' WHERE id=" . $attributes['id'] . ";");
                $result = $insertSentence->execute();
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
         * Search a user by email and return the password hash
         * 
         * @param string $mail user mail (Unique in table)
         * @return string user password hash
         */
        public static function login(string $mail) {
            $db = Db::connect();
            $result = $db -> query("SELECT pass_user FROM user WHERE mail='$mail'");
            $arrayPass = $result -> fetch(PDO::FETCH_ASSOC);
            return $arrayPass['pass_user'];
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
            $communitiesUser = $db -> query("SELECT id_community, is_admin FROM user_community WHERE id_user=$idUser");
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