<?php 
    require_once("../model/class/Community.php");
    require_once("../model/crud/CommunityCRUD.php");
    require_once("Utilities.php");

    /**
     * Update a community in the table
     */
    function update() {
        if (isset($_POST['session_token']) && isset($_POST['id'])) {
            $idUser = processToken($_POST['session_token']);
            if (CommunityCRUD::isAdmin($idUser,$_POST['id']) == "1") {
                $community = new Community(
                    $_POST['id'],
                    $_POST['name_community'],
                    $_POST['description_community']
                );
                sendJsonSucess(CommunityCRUD::update($community));
            }
        }
    }

    /**
     * Remove a community in the table
     */
    function remove() {
        if (isset($_POST['session_token']) && isset($_POST['id'])) {
            $idUser = processToken($_POST['session_token']);
            if (CommunityCRUD::isAdmin($idUser,$_POST['id']) == "1") {
                $id = $_POST['id'];
                sendJsonSucess(CommunityCRUD::remove($id));
            }
        }
    }

    /**
     * Change a user post perrmission
     */
    function changePostPermission() {
        if (isset($_POST['session_token']) && isset($_POST['id']) && isset($_POST['id_user_to_update']) && isset($_POST['write_permission'])) {
            $idUser = processToken($_POST['session_token']);
            if (CommunityCRUD::isAdmin($idUser,$_POST['id']) == "1" && gettype(CommunityCRUD::isAdmin($_POST['id_user_to_update'],$_POST['id'])) == "string") {
                sendJsonSucess(CommunityCRUD::changePostPermission($_POST['id_user_to_update'],$_POST['id'],$_POST['write_permission']));
            }
        }
    }
    

    /**
     * Choose a action according to the value action in $_POST
     */
    function runActions() {
        if ( isset($_POST['action']) ) {
            if ($_POST['action'] == "update") {
                update();
            } elseif ($_POST['action'] == "remove") {
                remove();
            } elseif ($_POST['action'] == "changePostPermission") {
                changePostPermission();
            }
        }
    }

    runActions();
?>