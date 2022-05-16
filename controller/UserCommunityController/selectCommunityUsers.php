<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/Community.php");
    require_once("../../model/crud/CommunityCRUD.php");
    require_once("../Utilities.php");
    require_once("checkUserInCommunity.php");

    /**
     * Select all users of a community
     */
    function selectCommunityUsers() {
        if (checkUserInCommunity()) {
            $users = CommunityCRUD::selectCommunityUsers($_POST['id']);
            echo json_encode($users);
        }
    }

    selectCommunityUsers();
?>