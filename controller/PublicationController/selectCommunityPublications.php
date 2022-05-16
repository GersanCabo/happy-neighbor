<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/Community.php");
    require_once("../../model/crud/CommunityCRUD.php");
    require_once("../../model/class/Publication.php");
    require_once("../../model/crud/PublicationCRUD.php");
    require_once("../Utilities.php");

    /**
     * Select a community publications
     */
    function selectCommunityPublications() {
        if (isset($_POST['session_token']) && isset($_POST['id'])) {
            $idUser = processToken($_POST['session_token']);
            if (gettype(CommunityCRUD::isAdmin($idUser,intval($_POST['id']))) == "integer") {
                $id = $_POST['id'];
                $numPage = intval($_POST['num_page']);
                $publications = PublicationCRUD::selectCommunityPublications($id, $numPage);
                echo json_encode($publications);
            }
        }
    }

    selectCommunityPublications();
?>