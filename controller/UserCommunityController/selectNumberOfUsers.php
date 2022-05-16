<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/Community.php");
    require_once("../../model/crud/CommunityCRUD.php");
    require_once("../Utilities.php");
    require_once("checkUserInCommunity.php");

    /**
     * Select the number of community users
     */
    function selectNumberOfUsers() {
        if (checkUserInCommunity()) {
            $numberOfUsers = CommunityCRUD::selectNumberOfUsers($_POST['id']);
            echo json_encode($numberOfUsers);
        }
    }

    selectNumberOfUsers();
?>