<?php
    require_once(realpath($_SERVER['DOCUMENT_ROOT']).'/PHP/happy_neighbor/model/crud/SessionTokenCRUD.php');

    /**
     * Search the incoming token in the table and check if it is expired
     * 
     * @param string $token incoming session token
     * @return mixed id user or false
     */
    function processToken(string $token) {
        $result = false;
        $tokenInDB = SessionTokenCRUD::select($token);
        if ($tokenInDB) {
            $arrayAttr = $tokenInDB -> getAttributes();
            $timePassed = time() - strtotime($arrayAttr['date_token']);
            if ($timePassed < 86000) {
                $result = $arrayAttr['id_user'];
            }
        }
        return $result;
    }

    /**
     * Show a JSON according to the value of a bool
     * 
     * @param bool $result to valorate
     */
    function sendJsonSucess(bool $result) {
        if ($result) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    }
?>