<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/Community.php");
    require_once("../../model/crud/CommunityCRUD.php");
    require_once("../Utilities.php");

    /**
     * Change a user post perrmission
     */
    function changePostPermission() {
        if (isset($_POST['session_token']) && isset($_POST['id']) && isset($_POST['id_user_to_update']) && isset($_POST['write_permission'])) {
            $idUser = processToken($_POST['session_token']);
            if (CommunityCRUD::isAdmin($idUser,$_POST['id']) === 1) {
                sendJsonSucess(CommunityCRUD::changePostPermission($_POST['id_user_to_update'],$_POST['id'],$_POST['write_permission']));
            }
        }
    }

    changePostPermission();
?>