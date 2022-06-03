<?php 
    require_once(realpath($_SERVER['DOCUMENT_ROOT']).'/PHP/happy_neighbor/model/class/Utilities.php');

    class User {

        const REG_EXPR_STRING_WITH_SPACES = '/^[a-zA-ZÀ-ÿ]+(\s*[a-zA-ZÀ-ÿ]*)*[a-zA-ZÀ-ÿ ]+$/';
        const STRINGS_TO_VERIFY = [
            'name_user' => [
                self::REG_EXPR_STRING_WITH_SPACES,
                50,
                2
            ],
            'last_name' => [
                self::REG_EXPR_STRING_WITH_SPACES,
                80,
                2
            ],
            'mail' => [
                '/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/i',
                80,
                5
            ],
            'pass_user' => [
                '/^[\w]+( [\w]+)*$/',
                20,
                6
            ],
            'biography' => [
                '/.+/im',
                300,
                0
            ]
        ];

        private array $attributes = [];

        public function __construct(int $id, $nameUser, $lastName, $mail, $passUser, $profilePicture, $biography) {
            if ($id > 0 && Utilities::validateString(
                    [$nameUser, self::STRINGS_TO_VERIFY['name_user'][0], self::STRINGS_TO_VERIFY['name_user'][1], self::STRINGS_TO_VERIFY['name_user'][2]],
                    [$lastName, self::STRINGS_TO_VERIFY['last_name'][0], self::STRINGS_TO_VERIFY['last_name'][1], self::STRINGS_TO_VERIFY['last_name'][2]],
                    [$mail, self::STRINGS_TO_VERIFY['mail'][0], self::STRINGS_TO_VERIFY['mail'][1], self::STRINGS_TO_VERIFY['mail'][2]],
                    [$passUser, self::STRINGS_TO_VERIFY['pass_user'][0], self::STRINGS_TO_VERIFY['pass_user'][1], self::STRINGS_TO_VERIFY['pass_user'][2]],
                    [$biography, self::STRINGS_TO_VERIFY['biography'][0], self::STRINGS_TO_VERIFY['biography'][1], self::STRINGS_TO_VERIFY['biography'][2]]
                )) {
                    $this->attributes['id'] = $id;
                    $this->attributes['name_user'] = $nameUser;
                    $this->attributes['last_name'] = $lastName;
                    $this->attributes['mail'] = $mail;
                    $this->attributes['pass_user'] = $passUser;
                    $this->attributes['profile_picture'] = $profilePicture;
                    $this->attributes['biography'] = $biography;
                }
        }

        /**
         * Convert array to user object
         * 
         * @param array $user array with user parammeters
         * @return object object user 
         */
        public static function getUser(array $user) {
            return new User($user['id'], $user['name_user'], $user['last_name'], $user['mail'], $user['pass_user'], $user['profile_picture'], $user['biography']);
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

        /**
         * User class setter
         *
         * @param string $attribute attribute name
         * @param mixed $value attribute value
         */
        public function __set(string $attribute, $value) {
            if(Utilities::validateSetter($attribute, $value, self::STRINGS_TO_VERIFY)) {
                if ($attribute == "pass_user") {
                    password_hash($value,PASSWORD_BCRYPT);
                }
                $this->attributes[$attribute] = $value;
            }
        }
    }
?>