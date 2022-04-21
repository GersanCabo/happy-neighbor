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
        public static function selectCommunityUsers(int $idCommunity) {
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