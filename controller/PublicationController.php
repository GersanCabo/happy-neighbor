<?php 
    require_once("../model/class/Publication.php");
    require_once("../model/crud/PublicationCRUD.php");
    require_once("../model/class/Community.php");
    require_once("../model/crud/CommunityCRUD.php");
    require_once("Utilities.php");

    /**
     * Insert a publication in the table
     */
    function insert() {
        if (isset($_POST['session_token']) && isset($_POST['id_community'])) {
            $idUser = processToken($_POST['session_token']);
            if (CommunityCRUD::isAdmin($idUser,$_POST['id_community']) && CommunityCRUD::selectPermissionUser($idUser,$_POST['id_community'])) {
                $publication = new Publication(
                    1,
                    $_POST['text_publication'],
                    $idUser,
                    $_POST['id_community'],
                    $_POST['comment_to']
                );
                sendJsonSucess(PublicationCRUD::insert($publication));
            }
        }
    }

    /**
     * Add a like in a publication
     */
    function addLike() {
        if (isset($_POST['session_token']) && isset($_POST['id'])) {
            $idUser = processToken($_POST['session_token']);
            $publication = PublicationCRUD::select($_POST['id']);
            $publicationArray = $publication -> getAttributes();
            if (CommunityCRUD::isAdmin($idUser,$publicationArray['id_community']) && CommunityCRUD::selectPermissionUser($idUser,$publicationArray['id_community']) && !(PublicationCRUD::checkLikeUser($idUser,$publicationArray['id']))) {
                sendJsonSucess(PublicationCRUD::addLike($publicationArray['id'], $idUser));
            }
        }
    }

    /**
     * Remove a like of a publication
     */
    function removeLike() {
        if (isset($_POST['session_token']) && isset($_POST['id'])) {
            $idUser = processToken($_POST['session_token']);
            $publication = PublicationCRUD::select($_POST['id']);
            $publicationArray = $publication -> getAttributes();
            if (CommunityCRUD::isAdmin($idUser,$publicationArray['id_community']) && CommunityCRUD::selectPermissionUser($idUser,$publicationArray['id_community']) && PublicationCRUD::checkLikeUser($idUser,$publicationArray['id'])) {
                sendJsonSucess(PublicationCRUD::removeLike($publicationArray['id'], $idUser));
            }
        }
    }

    /**
     * Remove a publication
     */
    function remove() {
        if (isset($_POST['session_token']) && isset($_POST['id'])) {
            $idUser = processToken($_POST['session_token']);
            $publication = PublicationCRUD::select($_POST['id']);
            $publicationArray = $publication -> getAttributes();
            if (CommunityCRUD::isAdmin($idUser,$publicationArray['id_community']) && ($publicationArray['id_user'] == $idUser)) {
                sendJsonSucess(PublicationCRUD::remove($publicationArray['id']));
            }
        }
    }

    /**
     * Choose a action according to the value action in $_POST
     */
    function runActions() {
        if ( isset($_POST['action']) ) {
            if ($_POST['action'] == "insert") {
                insert();
            } else if ($_POST['action'] == "addLike") {
                addLike();
            } else if ($_POST['action'] == "removeLike") {
                removeLike();
            }
        }
    }

    runActions();
?>