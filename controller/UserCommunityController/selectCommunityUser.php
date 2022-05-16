<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/Community.php");
    require_once("../../model/crud/CommunityCRUD.php");
    require_once("../Utilities.php");
    require_once("checkUserInCommunity.php");

    /**
     * Select a user of a community
     */
    function selectCommunityUser() {
        if (checkUserInCommunity() && gettype(CommunityCRUD::isAdmin($_POST['id_user'],$_POST['id'])) == "integer") {
            $user = CommunityCRUD::selectCommunityUser($_POST['id_user']);
            echo json_encode($user);
        }
    }

    selectCommunityUser();
?>