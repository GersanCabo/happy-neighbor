<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/Community.php");
    require_once("../../model/crud/CommunityCRUD.php");
    require_once("../Utilities.php");

    /**
     * Insert a community in the table
     */
    function insert() {
        if (isset($_POST['session_token']) && isset($_POST['user_creator_id'])) {
            $idUser = processToken($_POST['session_token']);
            if ($idUser == $_POST['user_creator_id']) {
                $community = new Community(
                    1,
                    $_POST['name_community'],
                    $_POST['description_community'],
                    $_POST['user_creator_id']
                );
                sendJsonSucess(CommunityCRUD::insert($community));
            }
        }
    }

    insert();