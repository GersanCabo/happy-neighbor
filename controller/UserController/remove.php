<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/User.php");
    require_once("../../model/class/UserPassHash.php");
    require_once("../../model/crud/UserCRUD.php");
    require_once("../Utilities.php");

    /**
     * Remove a user in the table
     */
    function remove() {
        if (isset($_POST['session_token']) && isset($_POST['id'])) {
            $id = processToken($_POST['session_token']);
            if ($id == $_POST['id']) {
                $id = $_POST['id'];
                sendJsonSucess(UserCRUD::remove($id));
            }
        }
    }

    remove();
?>