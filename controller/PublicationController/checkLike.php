<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/Publication.php");
    require_once("../../model/crud/PublicationCRUD.php");
    require_once("../../model/class/Community.php");
    require_once("../../model/crud/CommunityCRUD.php");
    require_once("../Utilities.php");

    /**
     * Check if like exist in a publication
     */
    function checkLike() {
        if (isset($_POST['session_token']) && isset($_POST['id'])) {
            $idUser = processToken($_POST['session_token']);
            $publication = PublicationCRUD::select($_POST['id']);
            $publicationArray = $publication -> getAttributes();
            if (gettype(CommunityCRUD::isAdmin($idUser,$publicationArray['id_community'])) == "integer" ) {
                sendJsonSucess(PublicationCRUD::checkLikeUser($idUser,$_POST['id']));
            }
        }
    }

    checkLike();
?>