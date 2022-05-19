<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/User.php");
    require_once("../../model/class/UserPassHash.php");
    require_once("../../model/crud/UserCRUD.php");
    require_once("../Utilities.php");

    function insertRequest() {
        if (isset($_POST['session_token']) && isset($_POST['id_community'])) {
            $idUser = processToken($_POST['session_token']);
            if ($idUser) {
                $existRequest = UserCRUD::existRequest($idUser, $_POST['id_community']);
                if ($existRequest) {
                    echo json_encode([false,$existRequest]);
                } else {
                    sendJsonSucess(UserCRUD::insertRequest($idUser,$_POST['id_community'])); //Change this
                }
            }
        }
    }

    insertRequest();
?>