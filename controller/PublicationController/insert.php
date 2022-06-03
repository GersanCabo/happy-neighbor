<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/Publication.php");
    require_once("../../model/crud/PublicationCRUD.php");
    require_once("../../model/class/Community.php");
    require_once("../../model/crud/CommunityCRUD.php");
    require_once("../Utilities.php");

    /**
     * Insert a publication in the table
     */
    function insert() {
        if (isset($_POST['session_token']) && isset($_POST['id_community'])) {
            $idUser = processToken($_POST['session_token']);
            if (gettype(CommunityCRUD::isAdmin($idUser,$_POST['id_community'])) == "integer"  
                && CommunityCRUD::selectPermissionUser($idUser,$_POST['id_community'])) {
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

    insert();
?>