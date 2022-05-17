<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/Community.php");
    require_once("../../model/crud/CommunityCRUD.php");
    require_once("../Utilities.php");

    function insertInvitation() {
        if (isset($_POST['session_token']) && isset($_POST['id']) && isset($_POST['id_user'])) {
            $idUser = processToken($_POST['session_token']);
            if (CommunityCRUD::isAdmin($idUser,$_POST['id']) === 1) {
                $existInvitation = CommunityCRUD::existInvitation($_POST['id_user'], $_POST['id']);
                if ($existInvitation) {
                    echo json_encode([false,$existInvitation]);
                } else {
                    sendJsonSucess(CommunityCRUD::insertInvitation($_POST['id_user'], $_POST['id']));
                }
            }
        }
    }

    insertInvitation();
?>