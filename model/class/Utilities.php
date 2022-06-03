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
         * regular expression[1], max length[2] and min length[3]
         * @return boolean is a correct value
         */
        public static function validateString(...$listStringsToValidate): bool {
            if (count($listStringsToValidate) > 0) {
                foreach ($listStringsToValidate as $stringToValidate) {
                        if (gettype($stringToValidate[0]) == "string" && $stringToValidate[0] != null 
                            && $stringToValidate[0] != "" 
                            && (!( 
                                    preg_match($stringToValidate[1],$stringToValidate[0])) 
                                    || (strlen($stringToValidate[0]) > $stringToValidate[2] 
                                    && strlen($stringToValidate[0]) < $stringToValidate[3]) 
                                ) ) {
                            return false;
                        }
                }
            }
            return true;
        }
    }
?>