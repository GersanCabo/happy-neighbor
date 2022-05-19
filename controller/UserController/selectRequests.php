<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/User.php");
    require_once("../../model/class/UserPassHash.php");
    require_once("../../model/crud/UserCRUD.php");
    require_once("../Utilities.php");

    function selectRequests() {
        if (isset($_POST['session_token'])) {
            $idUser = processToken($_POST['session_token']);
            if ($idUser) {
                echo json_encode(UserCRUD::selectRequests($idUser));
            }
        }
    }

    selectRequests();
?>