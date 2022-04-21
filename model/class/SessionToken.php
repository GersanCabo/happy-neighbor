<?php 

    class SessionToken {

        private array $attributes = [];

        public function __construct(int $idUser, $token = null, $dateToken = null) {
            if ($idUser > 0 && (strlen($token) >= 10 || $token == null || $token == "")) {
                if ($token == null || $token == "") {
                    $token = self::generateToken($idUser);
                }
                $this->attributes['token'] = $token;
                $this->attributes['date_token'] = $dateToken;
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
            return new SessionToken($sessionToken['id_user'],$sessionToken['token'], $sessionToken['date_token']);
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
         * Generate a user token session
         * 
         * @param string $mail user mail
         * @param int $lengthToken length of the token to generate, <10
         * @return string new user session token
         */
        private static function generateToken(int $id, int $lengthToken = 15) {
            if ($lengthToken < 10) {
                $lengthToken = 10;
            }
            return bin2hex(random_bytes(($lengthToken - ($lengthToken % 2)) / 2)) . "-" . strval($id);
        }
    }
?>