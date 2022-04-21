<?php
    require_once("../model/Login.php");

    /**
     * Check that the email and password are correct and create a new session token
     */
    if (isset($_POST['mail']) && isset($_POST['pass_user'])) {
        $email = $_POST['mail'];
        $passUser = $_POST['pass_user'];
        $token = Login::login($email, $passUser); //New token to send to the frontend or false
        echo json_encode($token);
    }
?>