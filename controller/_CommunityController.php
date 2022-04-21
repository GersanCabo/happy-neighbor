<?php 
    require_once("../model/class/Community.php");
    require_once("../model/crud/CommunityCRUD.php");
    require_once("Utilities.php");

    /**
     * Show a JSON according to the value of a bool
     * 
     * @param bool $result to valorate
     */
    function sendJsonSucess(bool $result) {
        if ($result) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    }

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
                    $_POST['total_money'],
                    $_POST['user_creator_id']
                );
                sendJsonSucess(CommunityCRUD::insert($community));
            }
        }
    }

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
                    $_POST['description_community'],
                    $_POST['total_money']
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
     * Select a community in the table
     */
    function select() {
        if (isset($_POST['session_token']) && isset($_POST['id'])) {
            $idUser = processToken($_POST['session_token']);
            if (gettype(CommunityCRUD::isAdmin($idUser,$_POST['id'])) == "string") {
                $id = $_POST['id'];
                $community = CommunityCRUD::select($id);
                echo json_encode($community -> getAttributes());
            }
        }
    }

    function selectCommunityUsers() {
        if (isset($_POST['session_token']) && isset($_POST['id'])) {
            $idUser = processToken($_POST['session_token']);
            if (gettype(CommunityCRUD::isAdmin($idUser,$_POST['id'])) == "string") {
                $id = $_POST['id'];
                $users = CommunityCRUD::selectCommunityUsers($id);
                echo json_encode($users);
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
            } elseif ($_POST['action'] == "update") {
                update();
            } elseif ($_POST['action'] == "remove") {
                remove();
            } elseif ($_POST['action'] == "select") {
                select();
            } elseif ($_POST['action'] == "selectCommunityUsers") {
                selectCommunityUsers();
            }
        }
    }

    runActions();
?>