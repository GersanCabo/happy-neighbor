<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/Community.php");
    require_once("../../model/crud/CommunityCRUD.php");
    require_once("../Utilities.php");

    /**
     * Select a community in the table
     */
    function select() {
        if (isset($_POST['session_token']) && isset($_POST['id'])) {
            $idUser = processToken($_POST['session_token']);
            if (gettype(CommunityCRUD::isAdmin($idUser,intval($_POST['id']))) == "integer") {
                $id = $_POST['id'];
                $community = CommunityCRUD::select($id);
                echo json_encode($community -> getAttributes());
            }
        }
    }

    select();
?>