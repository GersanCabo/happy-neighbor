<?php 
    class UserPassHash {

        private array $attributes = [];

        public function __construct(int $id, string $name_user, $last_name, string $mail, string $pass_user, $profile_picture, $biography) {
                $this->attributes['id'] = $id;
                $this->attributes['name_user'] = $name_user;
                $this->attributes['last_name'] = $last_name;
                $this->attributes['mail'] = $mail;
                $this->attributes['pass_user'] = $pass_user;
                $this->attributes['profile_picture'] = $profile_picture;
                $this->attributes['biography'] = $biography;
        }

        /**
         * Convert array to user object
         * 
         * @param array $user array with user parammeters
         * @return object object user 
         */
        public static function getUser(array $user) {
            return new UserPassHash(intval($user['id']), $user['name_user'], $user['last_name'], $user['mail'], $user['pass_user'], $user['profile_picture'], $user['biography']);
        }

        /**
         * Return attributes of user object
         * 
         * @return array user attributes
         */
        public function getAttributes() {
            return $this->attributes;
        }

        /**
         * User class getter
         *
         * @param string $attribute attribute name
         * @return mixed attribute value
         */
        public function __get(string $attribute) {
            return $this->attributes[$attribute];
        }

    }
?>