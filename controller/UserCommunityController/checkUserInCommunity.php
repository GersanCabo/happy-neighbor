<?php 
    require_once("../../model/class/Community.php");
    require_once("../../model/crud/CommunityCRUD.php");
    require_once("../Utilities.php");

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
?>