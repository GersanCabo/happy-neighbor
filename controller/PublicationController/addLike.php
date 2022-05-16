<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/Publication.php");
    require_once("../../model/crud/PublicationCRUD.php");
    require_once("../../model/class/Community.php");
    require_once("../../model/crud/CommunityCRUD.php");
    require_once("../Utilities.php");

    /**
     * Add a like in a publication
     */
    function addLike() {
        if (isset($_POST['session_token']) && isset($_POST['id'])) {
            $idUser = processToken($_POST['session_token']);
            $publication = PublicationCRUD::select($_POST['id']);
            $publicationArray = $publication -> getAttributes();
            if (gettype(CommunityCRUD::isAdmin($idUser,$publicationArray['id_community'])) == "integer"  && CommunityCRUD::selectPermissionUser($idUser,$publicationArray['id_community']) && !(PublicationCRUD::checkLikeUser($idUser,$publicationArray['id']))) {
                sendJsonSucess(PublicationCRUD::addLike($publicationArray['id'], $idUser));
            }
        }
    }

    addLike();
?>