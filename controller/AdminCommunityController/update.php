<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/Community.php");
    require_once("../../model/crud/CommunityCRUD.php");
    require_once("../Utilities.php");

    /**
     * Update a community in the table
     */
    function update() {
        if (isset($_POST['session_token']) && isset($_POST['id'])) {
            $idUser = processToken($_POST['session_token']);
            if (CommunityCRUD::isAdmin($idUser,$_POST['id']) === 1) {
                $community = new Community(
                    $_POST['id'],
                    $_POST['name_community'],
                    $_POST['description_community']
                );
                sendJsonSucess(CommunityCRUD::update($community));
            }
        }
    }

    update();
?>