<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/Community.php");
    require_once("../../model/crud/CommunityCRUD.php");
    require_once("../Utilities.php");

    function selectInvitations() {
        if (isset($_POST['session_token']) && isset($_POST['id'])) {
            $idUser = processToken($_POST['session_token']);
            if (CommunityCRUD::isAdmin($idUser,$_POST['id']) === 1) {
                echo json_encode(CommunityCRUD::selectInvitations($_POST['id']));
            }
        }
    }

    selectInvitations();
?>