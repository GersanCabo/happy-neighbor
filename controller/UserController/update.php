<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/User.php");
    require_once("../../model/class/UserPassHash.php");
    require_once("../../model/crud/UserCRUD.php");
    require_once("../Utilities.php");

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

    update();
?>