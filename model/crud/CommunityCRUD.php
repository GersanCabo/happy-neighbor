<?php 
    require_once(realpath($_SERVER['DOCUMENT_ROOT']).'/PHP/happy_neighbor/model/Connection.php');

    class CommunityCRUD {

        private const NOT_NULL_PARAMETERS = [
            'name_community',
            'user_creator_id'
        ];

        public function __construct() {}

        /**
         * Insert a community in the table and return the result
         * 
         * @param object community object
         * @return bool if the community is inserted or not
         */
        public static function insert(Community $community):bool {
            $result = false;
            $db = Db::connect();
            $isPossibleInsertCommunity = true;
            $attributes = $community -> getAttributes();
            foreach (self::NOT_NULL_PARAMETERS as $parameters) {
                if ($attributes[$parameters] == null || $attributes[$parameters] == "") {
                    $isPossibleInsertCommunity = false;
                    break;
                }
            }
            if ($isPossibleInsertCommunity) {
                $insertSentence = $db -> prepare("INSERT INTO community VALUES(null,'" . $attributes['name_community'] . "','" . $attributes['description_community'] . "'," . $attributes['user_creator_id'] . ",null);");
                $result = $insertSentence->execute();
            }
            return $result; 
        }

        /**
         * Update a community in the table and return the result
         * 
         * @param object community object
         * @return bool if the community is updated or not
         */
        public static function update(Community $community): bool {
            $result = false;
            $db = Db::connect();
            $attributes = $community -> getAttributes();
            $idCommunity = $attributes['id'];
            unset($attributes['id']);
            if ( self::select($idCommunity) ) {
                $sentenceUpdate = "UPDATE community SET ";
                foreach ($attributes as $index => $val) {
                    if ($val != "" || $val != null) {
                        if (gettype($val) == "string") {
                            $sentenceUpdate .= $index . "='" . $val . "',";
                        } else {
                            $sentenceUpdate .= $index . "=" . $val . ",";
                        }
                    }
                }
                $sentenceUpdate = substr($sentenceUpdate,0,-1);
                $sentenceUpdate .= " WHERE id=" . $idCommunity . ";";
                $updateSentence = $db -> prepare($sentenceUpdate);
                $result = $updateSentence->execute();
            }
            return $result;
        }

        /**
         * Remove a community in the table and return the result
         * 
         * @param int $id community id (Primary key)
         * @return bool if the community is removed or not
         */
        public static function remove(int $id): bool {
            $result = false;            
            $db = Db::connect();
            if ( self::select( $id ) && $id > 0) {
                $insertSentence = $db -> prepare("DELETE FROM community WHERE id=$id;");
                $result = $insertSentence->execute();
            }
            return $result;
        }

        /**
         * Search a community in the table and return it if found
         * 
         * @param int $id community id (Primary key)
         * @return mixed object UserPassHash or false if not located 
         */
        public static function select(int $id) {
            $community = false;
            $db = Db::connect();
            $result = $db -> query("SELECT * FROM community WHERE id=$id;");
            if ($arrayCommunity = $result -> fetch(PDO::FETCH_ASSOC)) {
                $community = Community::getCommunity($arrayCommunity);
            }
            return $community;
        }

        /**
         * Select the users of a community
         * 
         * @param int $idCommunity community id
         * @return array users of the community
         */
        public static function selectCommunityUsers(int $idCommunity):array {
            $users = [];
            $db = Db::connect();
            $usersCommunity = $db -> query("SELECT id_user, is_admin FROM user_community WHERE id_community=$idCommunity AND user_accepted=true AND community_accepted=true;");
            while ($user = $usersCommunity -> fetch(PDO::FETCH_ASSOC)) {
                $userSelect = $db -> query("SELECT name_user, last_name, profile_picture, biography FROM user WHERE id=" . $user['id_user'] . ";");
                if ($userAttr = $userSelect -> fetch(PDO::FETCH_ASSOC)) {
                    $users[] = [$userAttr,$user];
                }
            }
            return $users;
        }

        /**
         * Select the number of community users
         * 
         * @param int $idCommunity community id
         * @return int number of community users
         */
        public static function selectNumberOfUsers(int $idCommunity):int {
            $db = Db::connect();
            $numberOfUsersSentence = $db -> query("SELECT COUNT(id_user) FROM user_community WHERE id_community=$idCommunity AND user_accepted=true AND community_accepted=true;");
            $number = $numberOfUsersSentence -> fetch(PDO::FETCH_ASSOC);
            return intval($number['COUNT(id_user)']);
        }

        /**
         * Select the status of the user permission to publish posts, comment and vote
         * 
         * @param int $idUser user id
         * @param int $idCommunity community id
         * @return bool $userPermission status of the user permission to publish posts, comment and vote
         */
        public static function selectPermissionUser(int $idUser, int $idCommunity) {
            $userPermission = false;
            $db = Db::connect();
            $userPermissionSentence = $db -> query("SELECT write_permission FROM user_community WHERE id_user=$idUser AND id_community=$idCommunity");
            while ($userPermissionArray = $userPermissionSentence -> fetch(PDO::FETCH_ASSOC)) {
                $userPermission = $userPermissionArray['write_permission'];
            }
            return $userPermission;
        }

        /**
         * Change the permission of te user to publish posts, comment and vote
         * 
         * @param int $idUser user id
         * @param int $idCommunity community id
         * @param bool $writePermission if the user have permission to publish posts, comment and vote
         * @return bool $result the user permission is changed or not
         */
        public static function changePostPermission(int $idUser, int $idCommunity, bool $writePermission):bool {            
            $db = Db::connect();
            $writePermissionString = "false";
            if ($writePermission) {
                $writePermissionString = "true";
            }
            $insertSentence = $db -> prepare("UPDATE user_community SET write_permission=$writePermissionString WHERE id_user=$idUser AND id_community=$idCommunity");
            $result = $insertSentence->execute();
            return $result;
        }

        /**
         * Check if the user is admin or not
         * 
         * @param int $idUser user id
         * @param int $idCommunity community id
         * @return $result with these values:
         *      "0": if not a admin
         *      "1": if a admin
         *      false: if not a community user
         */
        public static function isAdmin(int $idUser, int $idCommunity) {
            $result = false;
            $db = Db::connect();
            $userRoleSentence = $db -> query("SELECT is_admin, user_accepted, community_accepted FROM user_community WHERE id_user=$idUser AND id_community=$idCommunity;");
            while ($userRole = $userRoleSentence -> fetch(PDO::FETCH_ASSOC)) {
                if (boolval($userRole['user_accepted']) && boolval($userRole['community_accepted'])) {
                    $result = intval($userRole['is_admin']);
                }
            }
            return $result;
        }

        /**
         * Select a community user
         * 
         * @param int $idUser user id
         * @return array $userInfo array with user data or empty
         */
        public static function selectCommunityUser(int $idUser):array {
            $userInfo = [];
            $db = Db::connect();
            $userIsAdmin = $db -> query("SELECT user_community.is_admin, user.name_user, user.last_name, user.profile_picture FROM user_community INNER JOIN user ON user_community.id_user = user.id WHERE id_user=$idUser AND user_accepted=true AND community_accepted=true;");
            while ($user = $userIsAdmin -> fetch(PDO::FETCH_ASSOC)) {
                $userInfo = $user;
            }
            return $userInfo;
        }

        /**
         * Check if exist a invitation in the table
         * 
         * @param int $idUser user id
         * @param int $idCommunity community id
         * @return $result a array with data or false
         */
        public static function existInvitation(int $idUser, int $idCommunity) {
            $result = false;
            $db = Db::connect();
            $existInvitationSentence = $db -> query("SELECT user_accepted, community_accepted FROM user_community WHERE id_user=$idUser AND id_community=$idCommunity;");
            if ($fetchExistInvitationSentence = $existInvitationSentence -> fetch(PDO::FETCH_ASSOC)) {
                $result = $fetchExistInvitationSentence;
            }
            return $result;
        }

        /**
         * Insert a new invitation
         * 
         * @param int $idUser user id
         * @param int $idCommunity community id
         * @return bool $result if invitation inserted or not
         */
        public static function insertInvitation(int $idUser, int $idCommunity):bool {
            $result = false;
            $db = Db::connect();
            $insertInvitationSentence = $db -> prepare("INSERT INTO user_community (id_user, id_community, community_accepted) VALUES ($idUser, $idCommunity, true);");
            $result = $insertInvitationSentence -> execute();
            return $result;
        }

        /**
         * Select all sended invitations and pending requests
         * 
         * @param int $idCommunity community id
         * @return array with invitations and requests or empty
         */
        public static function selectInvitations(int $idCommunity):array {
            $arraySended = [];
            $arrayReceived = [];
            $db = Db::connect();
            $sendedInvitationsSentence = $db -> query("SELECT user.id, user.name_user, user.last_name, user.profile_picture, user.biography, user_community.is_admin FROM user_community INNER JOIN user ON user_community.id_user = user.id WHERE id_community=$idCommunity AND user_accepted=false;");
            $receivedRequestsSentence = $db -> query("SELECT user.id, user.name_user, user.last_name, user.profile_picture, user.biography, user_community.is_admin FROM user_community INNER JOIN user ON user_community.id_user = user.id WHERE id_community=$idCommunity AND community_accepted=false;");
            while ($fetchSendedInvitationsSentence = $sendedInvitationsSentence -> fetch(PDO::FETCH_ASSOC)) {
                $arraySended[] = $fetchSendedInvitationsSentence;
            }
            while ($fetchReceivedRequestsSentence = $receivedRequestsSentence -> fetch(PDO::FETCH_ASSOC)) {
                $arrayReceived[] = $fetchReceivedRequestsSentence;
            }
            return [$arrayReceived,$arraySended];
        }

        /**
         * Accept a request sended by a user to the community
         * 
         * @param int $idUser id of the user who sended the request
         * @return bool if the request is accepted or not
         */
        public static function acceptRequest(int $idUser, int $idCommunity):bool {
            $result = false;
            $db = Db::connect();
            $acceptRequestSentence = $db -> prepare("UPDATE user_community SET community_accepted=true WHERE id_user=$idUser AND id_community=$idCommunity AND community_accepted=false;");
            $result = $acceptRequestSentence -> execute();
            return $result;
        }

        /**
         * Remove a user request
         * 
         * @param int $idUser user id
         * @param int $idCommunity community id
         * @return bool $result if request is removed or not
         */
        public static function removeRequest(int $idUser, int $idCommunity, bool $isInvitation = false):bool {
            $result = false;
            $db = Db::connect();
            $communityAcceptedString = "false";
            $userAcceptedString = "true";
            if ($isInvitation) {
                $communityAcceptedString = "true";
                $userAcceptedString = "false";
            }
            $removeRequestSentence = $db -> prepare("DELETE FROM user_community WHERE id_user=$idUser AND id_community=$idCommunity AND community_accepted=$communityAcceptedString AND user_accepted=$userAcceptedString;");
            $result = $removeRequestSentence -> execute();
            return $result;
        }
    }
?>