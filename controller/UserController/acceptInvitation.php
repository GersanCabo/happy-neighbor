<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/User.php");
    require_once("../../model/class/UserPassHash.php");
    require_once("../../model/crud/UserCRUD.php");
    require_once("../Utilities.php");

    /**
     * Accept invitation sended by a community
     */
    function acceptInvitation() {
        if (isset($_POST['session_token']) && isset($_POST['id_community'])) {
            $idUser = processToken($_POST['session_token']);
            if ($idUser) {
                $existRequest = UserCRUD::existRequest($idUser, $_POST['id_community']);
                if ($existRequest) {
                   sendJsonSucess(UserCRUD::acceptInvitation($idUser,$_POST['id_community']));
                }
            }
        }
    }

    acceptInvitation();
?>