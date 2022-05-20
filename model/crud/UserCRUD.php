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
         * Select the communities of a user
         * 
         * @param int $idUser user id
         * @return array communities of the user
         */
        public static function selectUserCommunities(int $idUser) {
            $communities = [];
            $db = Db::connect();
            $communitiesUser = $db -> query("SELECT user_community.id_community, community.name_community, community.description_community, user_community.is_admin FROM user_community INNER JOIN community ON user_community.id_community = community.id WHERE id_user=$idUser AND user_accepted=true AND community_accepted=true;");
            while ($community = $communitiesUser -> fetch(PDO::FETCH_ASSOC)) {
                $communities[] = [$community['id_community'],$community['name_community'],$community['description_community'],$community['is_admin']];
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
                $usersCommunity = $db -> query("SELECT id_user, is_admin FROM user_community WHERE id_community=$arrayCommunity[0] AND invitation_accepted=true;");
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

        /**
         * Select all sended invitations and pending requests
         * 
         * @param int $idUser id user
         * @return array with invitations and requests or empty
         */
        public static function selectRequests(int $idUser):array {
            $arraySended = [];
            $arrayReceived = [];
            $db = Db::connect();
            $receivedInvitationsSentence = $db -> query("SELECT community.id, community.name_community, community.description_community FROM user_community INNER JOIN community ON user_community.id_community = community.id WHERE id_user=$idUser AND user_accepted=false;");
            $sendedRequestsSentence = $db -> query("SELECT community.id, community.name_community, community.description_community FROM user_community INNER JOIN community ON user_community.id_community = community.id WHERE id_user=$idUser AND community_accepted=false;");
            while ($fetchReceivedInvitationsSentence = $receivedInvitationsSentence -> fetch(PDO::FETCH_ASSOC)) {
                $arrayReceived[] = $fetchReceivedInvitationsSentence;
            }
            while ($fetchSendedRequestsSentence = $sendedRequestsSentence -> fetch(PDO::FETCH_ASSOC)) {
                $arraySended[] = $fetchSendedRequestsSentence;
            }
            return [$arrayReceived,$arraySended];
        }

        /**
         * Check if exist a request in the table
         * 
         * @param int $idUser user id
         * @param int $idCommunity community id
         * @return $result a array with data or false
         */
        public static function existRequest(int $idUser, int $idCommunity) {
            $result = false;
            $db = Db::connect();
            $existRequestSentence = $db -> query("SELECT user_accepted, community_accepted FROM user_community WHERE id_user=$idUser AND id_community=$idCommunity;");
            if ($fetchExistRequestSentence = $existRequestSentence -> fetch(PDO::FETCH_ASSOC)) {
                $result = $fetchExistRequestSentence;
            }
            return $result;
        }

        /**
         * Insert a new request
         * 
         * @param int $idUser user id
         * @param int $idCommunity community id
         * @return bool $result if request inserted or not
         */
        public static function insertRequest(int $idUser, int $idCommunity): bool {
            $result = false;
            $db = Db::connect();
            $insertRequestSentence = $db -> prepare("INSERT INTO user_community (id_user, id_community, user_accepted) VALUES ($idUser, $idCommunity, true);");
            $result = $insertRequestSentence -> execute();
            return $result;
        }

        public static function acceptInvitation(int $idUser, int $idCommunity) {
            $result = false;
            $db = Db::connect();
            $acceptInvitationSentence = $db -> prepare("UPDATE user_community SET user_accepted=true WHERE id_user=$idUser AND id_community=$idCommunity AND user_accepted=false;");
            $result = $acceptInvitationSentence -> execute();
            return $result;
        }

        public static function removeInvitation(int $idUser, int $idCommunity, bool $isRequest = false) {
            $result = false;
            $db = Db::connect();
            $communityAcceptedString = "true";
            $userAcceptedString = "false";
            if ($isRequest) {
                $communityAcceptedString = "false";
                $userAcceptedString = "true";
            }
            $removeInvitationSentence = $db -> prepare("DELETE FROM user_community WHERE id_user=$idUser AND id_community=$idCommunity AND community_accepted=$communityAcceptedString AND user_accepted=$userAcceptedString;");
            $result = $removeInvitationSentence -> execute();
            return $result;
        }
    }
?>