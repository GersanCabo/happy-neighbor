<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/Community.php");
    require_once("../../model/crud/CommunityCRUD.php");
    require_once("../Utilities.php");

    /**
     * Remove a incoming request sended by a user
     */
    function removeRequest() {
        if (isset($_POST['session_token']) && isset($_POST['id_community']) && isset($_POST['id_user']) ) {
            $idUser = processToken($_POST['session_token']);
            if (CommunityCRUD::isAdmin($idUser,$_POST['id_community']) === 1) {
                $existInvitation = CommunityCRUD::existInvitation($_POST['id_user'], $_POST['id_community']);
                if ($existInvitation) {
                   sendJsonSucess(CommunityCRUD::removeRequest($_POST['id_user'],$_POST['id_community']));
                }
            }
        }
    }

    removeRequest();
?>