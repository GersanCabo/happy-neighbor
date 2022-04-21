<?php 
    require_once(realpath($_SERVER['DOCUMENT_ROOT']).'/PHP/happy_neighbor/model/Connection.php');
    require_once(realpath($_SERVER['DOCUMENT_ROOT']).'/PHP/happy_neighbor/model/class/SessionToken.php');

    class SessionTokenCRUD {

        public function __construct() {}

        /**
         * Insert a session token in the table and return the token
         * 
         * @param object SessionToken object
         * @return mixed new session token or false if not inserted
         */
        public static function insert(SessionToken $sessionToken) {
            $tokenToSend = false;
            $db = Db::connect();
            $attributes = $sessionToken -> getAttributes();
            $dropSentence = $db -> prepare("DELETE FROM session_token WHERE id_user=" . $attributes['id_user'] . ";");
            $isDroped = $dropSentence->execute();
            if ($isDroped) {
                $insertSentence = $db -> prepare("INSERT INTO session_token VALUES ('" . $attributes['token'] . "',null," . $attributes['id_user'] . ");");
                $isInserted = $insertSentence->execute();
                if ($isInserted) {
                    $tokenToSend = $attributes['token'];
                }
            }
            return $tokenToSend; 
        }

        /**
         * Search a token in the table and return it if found
         * 
         * @param string $token user session token (Primary key)
         * @return mixed object SessionToken or false
         */
        public static function select(string $token) {
            $sessionToken = false;
            $db = Db::connect();
            $result = $db -> query("SELECT * FROM session_token WHERE token='$token';");
            if ($arraySessionToken = $result -> fetch(PDO::FETCH_ASSOC)) {
                $sessionToken = SessionToken::getSessionToken($arraySessionToken);
            }
            return $sessionToken;
        }
    }
?>