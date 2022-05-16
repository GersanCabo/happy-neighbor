<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/User.php");
    require_once("../../model/class/UserPassHash.php");
    require_once("../../model/crud/UserCRUD.php");
    require_once("../Utilities.php");

    /**
     * Validate if token is validate or not
     */
    function validateToken() {
        if (isset($_POST['session_token'])) {
            $result = false;
            $id = processToken($_POST['session_token']);
            if ($id) {
                $result = true;
            }
            sendJsonSucess($result);
        }
    }

    validateToken();

?>