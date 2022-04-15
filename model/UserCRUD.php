<?php 
    require_once('Connection.php');

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
            $db = Db::connect();
            if ( self::select( $id ) && $id > 0) {
                $insertSentence = $db -> prepare("DELETE FROM user WHERE id=$id");
                $result = $insertSentence->execute();
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

    }
?>