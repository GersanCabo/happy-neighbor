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

    function addLike() {
        if (isset($_POST['session_token']) && isset($_POST['id'])) {
            $idUser = processToken($_POST['session_token']);
            $publication = PublicationCRUD::select($_POST['id']);
            if (CommunityCRUD::isAdmin($idUser,$publication['id_community']) && CommunityCRUD::selectPermissionUser($idUser,$publication['id_community']) && !(PublicationCRUD::checkLikeUser($idUser,$publication['id']))) {
                sendJsonSucess(PublicationCRUD::addLike($publication['id'], $idUser));
            }
        }
    }

    function removeLike() {
        if (isset($_POST['session_token']) && isset($_POST['id'])) {
            $idUser = processToken($_POST['session_token']);
            $publication = PublicationCRUD::select($_POST['id']);
            if (CommunityCRUD::isAdmin($idUser,$publication['id_community']) && CommunityCRUD::selectPermissionUser($idUser,$publication['id_community']) && PublicationCRUD::checkLikeUser($idUser,$publication['id'])) {
                sendJsonSucess(PublicationCRUD::removeLike($publication['id'], $idUser));
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
            }
        }
    }

    runActions();
?>