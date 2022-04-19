<?php 
    require_once("../model/class/User.php");
    require_once("../model/class/UserPassHash.php");
    require_once("../model/crud/UserCRUD.php");

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

    /**
     * Remove a user in the table
     */
    function remove() {
        $id = $_POST['id'];
        sendJsonSucess(UserCRUD::remove($id));
    }

    /**
     * Select a user in the table
     */
    function select() {
        $id = $_POST['id'];
        $user = UserCRUD::select($id);
        echo json_encode($user -> getAttributes());
    }

    /**
     * Search a user and verify the password
     */
    function login() {
        $email = $_POST['mail'];
        $passUser = $_POST['pass_user'];
        $token = UserCRUD::login($email, $passUser);
        //$result = password_verify($_POST['pass_user'], $passDb);
        echo $token; //JSON please
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
            } elseif ($_POST['action'] == "login") {
                login();
            }
        }
    }

    runActions();
?>