<?php 
    class Utilities {
        /**
         * Validate setter value
         * 
         * @param string $attribute attribute name
         * @param mixed $value attribute value
         * @return boolean is a correct value
         */
        public static function validateSetter(string $attribute, $value, $stringsToVerify) {
            $isValueCorrect = true;
            $arrayParameters = [];
            if (array_key_exists($attribute, $stringsToVerify)) {
                $arrayParameters = $stringsToVerify[$attribute];
                array_unshift($arrayParameters, $value);
            }
            if ( ($attribute == 'id' && $value <= 0) || !( self::validateString($arrayParameters) ) ) {
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
        public static function validateString(...$listStringsToValidate): bool {
            if (count($listStringsToValidate) > 0) {
                foreach ($listStringsToValidate as $stringToValidate) {
                    if ($stringToValidate[0] == null || $stringToValidate[0] == "") {
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

        /**
         * Aqui vas aa retornar el hash de la contraseña y luego en el controlador compruebas que coincida
         * con el que ha pasado el usuario. Crack.
         */
        public static function returnPassHash() {
            $db = Db::connect();
            $result = $db -> query("SELECT pass_user FROM user WHERE mail='$mail'");
            $arrayPass = $result -> fetch(PDO::FETCH_ASSOC);
            return $arrayPass['pass_user'];
        }
    }
?>