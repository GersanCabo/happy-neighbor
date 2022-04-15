<?php 
    class User {

        const REG_EXPR_STRING_WITH_SPACES = '/^[\w]+( [\w]+)*$/';
        const STRINGS_TO_VERIFY = [
            'name_user' => [
                self::REG_EXPR_STRING_WITH_SPACES,
                50,
                2,
                true
            ],
            'last_name' => [
                self::REG_EXPR_STRING_WITH_SPACES,
                80,
                2,
                false
            ],
            'mail' => [
                '/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/i',
                80,
                5,
                true
            ],
            'pass_user' => [
                self::REG_EXPR_STRING_WITH_SPACES,
                20,
                6,
                true
            ],
            'biography' => [
                '/.+/im',
                300,
                0,
                false
            ]
        ];

        private array $attributes = [];

        public function __construct($id, $name_user, $last_name, $mail, $pass_user, $profile_picture, $biography) {
            if ($id > 0 && $this->validateString(
                    [$name_user, self::STRINGS_TO_VERIFY['name_user'][0], self::STRINGS_TO_VERIFY['name_user'][1], self::STRINGS_TO_VERIFY['name_user'][2], self::STRINGS_TO_VERIFY['name_user'][3]],
                    [$last_name, self::STRINGS_TO_VERIFY['last_name'][0], self::STRINGS_TO_VERIFY['last_name'][1], self::STRINGS_TO_VERIFY['last_name'][2], self::STRINGS_TO_VERIFY['last_name'][3]],
                    [$mail, self::STRINGS_TO_VERIFY['mail'][0], self::STRINGS_TO_VERIFY['mail'][1], self::STRINGS_TO_VERIFY['mail'][2], self::STRINGS_TO_VERIFY['mail'][3]],
                    [$pass_user, self::STRINGS_TO_VERIFY['pass_user'][0], self::STRINGS_TO_VERIFY['pass_user'][1], self::STRINGS_TO_VERIFY['pass_user'][2], self::STRINGS_TO_VERIFY['pass_user'][3]],
                    [$biography, self::STRINGS_TO_VERIFY['biography'][0], self::STRINGS_TO_VERIFY['biography'][1], self::STRINGS_TO_VERIFY['biography'][2], self::STRINGS_TO_VERIFY['biography'][3]]
                )) {
                    $this->attributes['id'] = $id;
                    $this->attributes['name_user'] = $name_user;
                    $this->attributes['last_name'] = $last_name;
                    $this->attributes['mail'] = $mail;
                    $this->attributes['pass_user'] = password_hash($pass_user, PASSWORD_BCRYPT);
                    $this->attributes['profile_picture'] = $profile_picture;
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
            if($this->validateSetter($attribute, $value)) {
                if ($attribute == "pass_user") {
                    password_hash($value,PASSWORD_BCRYPT);
                }
                $this->attributes[$attribute] = $value;
            }
        }

        /**
         * Validate setter value
         * 
         * @param string $attribute attribute name
         * @param mixed $value attribute value
         * @return boolean is a correct value
         */
        private function validateSetter(string $attribute, $value) {
            $isValueCorrect = true;
            $arrayParameters = [];
            if (array_key_exists($attribute, self::STRINGS_TO_VERIFY)) {
                $arrayParameters = self::STRINGS_TO_VERIFY[$attribute];
                array_unshift($arrayParameters, $value);
            }
            if ( ($attribute == 'id' && $value <= 0) || !( $this->validateString($arrayParameters) ) ) {
                $isValueCorrect = false;
            }
            return $isValueCorrect;
        }

        /**
         * Validate string is correct
         * 
         * @param mixed ...$listStringsToValidate many arrays with value attribute[0], 
         * regular expression[1], max length[2], min length[3] and if the value can be null[4]
         * @return boolean is a correct value
         */
        private function validateString(...$listStringsToValidate): bool {
            if (count($listStringsToValidate) > 0) {
                foreach ($listStringsToValidate as $stringToValidate) {
                    if ($stringToValidate[0] == null) {
                        if ($stringToValidate[4]) {
                            return false;
                        }
                    } else if (!(preg_match($stringToValidate[1],$stringToValidate[0])) || (strlen($stringToValidate[0]) > $stringToValidate[2] && strlen($stringToValidate[0]) < $stringToValidate[3])) {
                        return false;
                    }
                }
            }
            return true;
        }

    }
?>