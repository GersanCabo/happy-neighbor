<?php 

    class SessionToken {

        private array $attributes = [];

        public function __construct(string $token, int $idUser, $expiredDate = null) {
            if ($idUser > 0 && strlen($token) >= 10) {
                    $this->attributes['token'] = $token;
                    $this->attributes['expired_date'] = $expiredDate;
                    $this->attributes['id_user'] = $idUser;
                }
        }

        /**
         * Convert array to SessionToken object
         * 
         * @param array $sessionToken array with row session token parammeters
         * @return object object SessionToken
         */
        public static function getSessionToken(array $sessionToken) {
            return new SessionToken($sessionToken['token'], $sessionToken['id_user'], $sessionToken['expired_date']);
        }

        /**
         * Return attributes of SessionToken object
         * 
         * @return array SessionToken attributes
         */
        public function getAttributes() {
            return $this->attributes;
        }

        /**
         * SessionToken class getter
         *
         * @param string $attribute attribute name
         * @return mixed attribute value
         */
        public function __get(string $attribute) {
            return $this->attributes[$attribute];
        }

        /**
         * SessionToken class setter
         *
         * @param string $attribute attribute name
         * @param mixed $value attribute value
         */
        public function __set(string $attribute, $value) {
            if (!( ($attribute == "id_user" && $value <= 0) || ($attribute == "token" && strlen($value) < 10) )) {
                $this->attributes[$attribute] = $value;
            }
        }
    }
?>