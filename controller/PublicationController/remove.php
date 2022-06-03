<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/Publication.php");
    require_once("../../model/crud/PublicationCRUD.php");
    require_once("../../model/class/Community.php");
    require_once("../../model/crud/CommunityCRUD.php");
    require_once("../Utilities.php");

    /**
     * Remove a publication
     */
    function remove() {
        if (isset($_POST['session_token']) && isset($_POST['id'])) {
            $idUser = processToken($_POST['session_token']);
            $publication = PublicationCRUD::select($_POST['id']);
            $publicationArray = $publication -> getAttributes();
            if (gettype(CommunityCRUD::isAdmin($idUser,$publicationArray['id_community'])) == "integer" 
                    && ($publicationArray['id_user'] == $idUser)) {
                sendJsonSucess(PublicationCRUD::remove($publicationArray['id']));
            }
        }
    }

    remove();
?>