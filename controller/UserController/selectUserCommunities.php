<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/User.php");
    require_once("../../model/class/UserPassHash.php");
    require_once("../../model/crud/UserCRUD.php");
    require_once("../Utilities.php");

    /**
     * Select all communities of the user
     */
    function selectUserCommunities() {
        if (isset($_POST['session_token'])) {
            $id = processToken($_POST['session_token']);
            if ($id) {
                $communities = UserCRUD::selectUserCommunities($id);
                echo json_encode($communities);
            } else {
                echo json_encode(0);
            }
        }
    }

    selectUserCommunities();
?>