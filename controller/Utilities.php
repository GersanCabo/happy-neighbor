<?php 
    require_once("../model/crud/SessionTokenCRUD.php");

    /**
     * Search the incoming token in the table and check if it is expired
     * 
     * @param string $token incoming session token
     * @return mixed id user or false
     */
    function processToken(string $token) {
        $tokenInDB = SessionTokenCRUD::select($token);
        $arrayAttr = $tokenInDB -> getAttributes();
        $timePassed = time() - strtotime($arrayAttr['date_token']);
        if ($timePassed > 86000) {
            return false;
        } else {
            return $arrayAttr['id_user'];
        }
    }
?>