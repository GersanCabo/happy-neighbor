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
        if (isset($_POST['session_token']) && isset($_POST['name_community'])) {
            $idUser = processToken($_POST['session_token']);
            if ($idUser) {
                $community = new Community(
                    1,
                    $_POST['name_community'],
                    $_POST['description_community'],
                    $idUser
                );
                sendJsonSucess(CommunityCRUD::insert($community));
            }
        }
    }

    insert();