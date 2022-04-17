<?php 
    require_once(realpath($_SERVER['DOCUMENT_ROOT']).'/PHP/happy_neighbor/model/Connection.php');

    class CommunityCRUD {

        public function __construct() {}

        /**
         * Insert a community in the table and return the result
         * 
         * @param object community object
         * @return bool if the community is inserted or not
         */
        public static function insert(Community $community):bool {
            $db = Db::connect();
            $attributes = $community -> getAttributes();
            $insertSentence = $db -> prepare("INSERT INTO community VALUES(null,'" . $attributes['name_community'] . "','" . $attributes['description_community'] . "'," . $attributes['total_money'] . ",null);");
            return $insertSentence->execute();
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
            if ( self::select($attributes['id']) ) {
                $insertSentence = $db -> prepare("UPDATE community SET name_community='" . $attributes['name_community'] . "',description_community='" . $attributes['description_community'] . "',total_money=" . $attributes['total_money'] . " WHERE id=" . $attributes['id'] . ";");
                $result = $insertSentence->execute();
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
                $insertSentence = $db -> prepare("DELETE FROM community WHERE id=$id");
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
            $result = $db -> query("SELECT * FROM community WHERE id=$id");
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
            $usersCommunity = $db -> query("SELECT id_user, is_admin FROM user_community WHERE id_community=$idCommunity");
            while ($user = $usersCommunity -> fetch(PDO::FETCH_ASSOC)) {
                $users[] = [$user['id_user'],$user['is_admin']];
            }
            return $users;
        }

    }
?>