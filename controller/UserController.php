<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../model/class/User.php");
    require_once("../model/class/UserPassHash.php");
    require_once("../model/crud/UserCRUD.php");
    require_once("Utilities.php");

    /**
     * Insert a user in the table
     */
    function insert() {
        $user = new User(
            1,
            $_POST['name_user'],
            $_POST['last_name'],
            $_POST['mail'],
            $_POST['pass_user'],
            $_POST['profile_picture'],
            $_POST['biography']
        );
        sendJsonSucess(UserCRUD::insert($user));
    }

    /**
     * Update a user in the table
     */
    function update() {
        if (isset($_POST['session_token']) && isset($_POST['id'])) {
            $id = processToken($_POST['session_token']);
            if ($id == $_POST['id']) {
                $user = new User(
                    $_POST['id'],
                    $_POST['name_user'],
                    $_POST['last_name'],
                    $_POST['mail'],
                    $_POST['pass_user'],
                    $_POST['profile_picture'],
                    $_POST['biography']
                );
                sendJsonSucess(UserCRUD::update($user));
            }
        }
    }

    /**
     * Remove a user in the table
     */
    function remove() {
        if (isset($_POST['session_token']) && isset($_POST['id'])) {
            $id = processToken($_POST['session_token']);
            if ($id == $_POST['id']) {
                $id = $_POST['id'];
                sendJsonSucess(UserCRUD::remove($id));
            }
        }
    }

    /**
     * Select a user in the table
     */
    function select() {
        if (isset($_POST['session_token'])) {
            $id = processToken($_POST['session_token']);
            if ($id) {
                $user = UserCRUD::select($id);
                echo json_encode($user -> getAttributes());
            }
        }
    }

    /**
     * Select all communities of the user
     */
    function selectUserCommunities() {
        if (isset($_POST['session_token'])) {
            $id = processToken($_POST['session_token']);
            if ($id) {
                $communities = UserCRUD::selectUserCommunities($id);
                echo json_encode($communities);
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
                } elseif ($_POST['action'] == "selectUserCommunities") {
                    selectUserCommunities();
                }
            }
    }

    runActions();
?>