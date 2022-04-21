<?php
    require_once(realpath($_SERVER['DOCUMENT_ROOT']).'/PHP/happy_neighbor/model/Connection.php');
    require_once(realpath($_SERVER['DOCUMENT_ROOT']).'/PHP/happy_neighbor/model/class/SessionToken.php');

    class Login {

        private function __construct() {}

        /**
         * Search a user by email, validate the password and return a session token
         * 
         * @param string $mail user mail (Unique in table)
         * @param string $passUser password to validate against the hash
         * @return mixed new user session token in db or false
         */
        public static function login(string $mail, string $passUser) {
            $db = Db::connect();
            $tokenToSend = false;
            $resultSelect = $db -> query("SELECT id,pass_user FROM user WHERE mail='$mail'");
            $arrayPass = $resultSelect -> fetch(PDO::FETCH_ASSOC);
            $isPassCorrect = password_verify($passUser, $arrayPass['pass_user']);
            if ($isPassCorrect) {
                $tokenToSend = SessionTokenCRUD::insert(new SessionToken(intval($arrayPass['id'])));
            }
            return $tokenToSend;
        }

    }
?>