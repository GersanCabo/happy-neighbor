<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../model/class/Community.php");
    require_once("../model/crud/CommunityCRUD.php");
    require_once("Utilities.php");

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

    /**
     * Select a community in the table
     */
    function select() {
        if (isset($_POST['session_token']) && isset($_POST['id'])) {
            $idUser = processToken($_POST['session_token']);
            if (gettype(CommunityCRUD::isAdmin($idUser,$_POST['id'])) == "integer") {
                $id = $_POST['id'];
                $community = CommunityCRUD::select($id);
                echo json_encode($community -> getAttributes());
            }
        }
    }

    /**
     * Select all users of a community
     */
    function selectCommunityUsers() {
        if (checkUserInCommunity()) {
            $users = CommunityCRUD::selectCommunityUsers($_POST['id']);
            echo json_encode($users);
        }
    }

    /**
     * Select the number of community users
     */
    function selectNumberOfUsers() {
        if (checkUserInCommunity()) {
            $numberOfUsers = CommunityCRUD::selectNumberOfUsers($_POST['id']);
            echo json_encode($numberOfUsers);
        }
    }

    /**
     * Check if a user is in the community
     * 
     * @return bool $result is a user in the community or not
     */
    function checkUserInCommunity():bool {
        $result = false;
        if (isset($_POST['session_token']) && isset($_POST['id'])) {
            $idUser = processToken($_POST['session_token']);
            if (gettype(CommunityCRUD::isAdmin($idUser,$_POST['id'])) == "integer") {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * Choose a action according to the value action in $_POST
     */
    function runActions() {
        if ( isset($_POST['action']) ) {
            if ($_POST['action'] == "insert") {
                insert();
            } elseif ($_POST['action'] == "select") {
                select();
            } elseif ($_POST['action'] == "selectCommunityUsers") {
                selectCommunityUsers();
            } elseif ($_POST['action'] == "selectNumberOfUsers") {
                selectNumberOfUsers();
            }
        }
    }

    runActions();
?>