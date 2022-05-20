<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/User.php");
    require_once("../../model/class/UserPassHash.php");
    require_once("../../model/crud/UserCRUD.php");
    require_once("../Utilities.php");

    /**
     * Select a user in the table
     */
    function select() {
        if (isset($_POST['session_token'])) {
            $id = processToken($_POST['session_token']);
            if ($id) {
                $user = UserCRUD::select($id);
                $userArray = $user -> getAttributes();
                unset($userArray['pass_user']);
                echo json_encode($userArray);
            }
        }
    }

    select();
?>