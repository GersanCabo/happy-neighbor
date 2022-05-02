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
            $usersCommunity = $db -> query("SELECT id_user, is_admin FROM user_community WHERE id_community=$idCommunity AND invitation_accepted=true;");
            while ($user = $usersCommunity -> fetch(PDO::FETCH_ASSOC)) {
                $userSelect = $db -> query("SELECT name_user, last_name, profile_picture, biography FROM user WHERE id=" . $user['id_user'] . ";");
                if ($userAttr = $userSelect -> fetch(PDO::FETCH_ASSOC)) {
                    $users[$user['id_user']] = [$userAttr,$user['is_admin']];
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
            $numberOfUsersSentence = $db -> query("SELECT COUNT(id_user) FROM user_community WHERE id_community=$idCommunity AND invitation_accepted=true;");
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
            $userRoleSentence = $db -> query("SELECT is_admin, invitation_accepted FROM user_community WHERE id_user=$idUser AND id_community=$idCommunity;");
            while ($userRole = $userRoleSentence -> fetch(PDO::FETCH_ASSOC)) {
                if (boolval($userRole['invitation_accepted'])) {
                    $result = $userRole['is_admin'];
                }
            }
            return $result;
        }
    }
?>