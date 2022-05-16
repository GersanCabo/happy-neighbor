<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/Community.php");
    require_once("../../model/crud/CommunityCRUD.php");
    require_once("../../model/class/Publication.php");
    require_once("../../model/crud/PublicationCRUD.php");
    require_once("../Utilities.php");

    /**
     * Select a community publication
     */
    function select() {
        if (isset($_POST['session_token']) && isset($_POST['id_community']) && isset($_POST['id'])) {
            $idUser = processToken($_POST['session_token']);
            if (gettype(CommunityCRUD::isAdmin($idUser,intval($_POST['id_community']))) == "integer") {
                $id = $_POST['id'];
                $publication = PublicationCRUD::select($id);
                echo json_encode($publication -> getAttributes());
            }
        }
    }

    select();
?>