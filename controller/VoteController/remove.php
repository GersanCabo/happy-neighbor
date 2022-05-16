<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/Vote.php");
    require_once("../../model/crud/VoteCRUD.php");
    require_once("../../model/class/Community.php");
    require_once("../../model/crud/CommunityCRUD.php");
    require_once("../Utilities.php");

    /**
     * Remove a vote
     */
    function remove() {
        if (isset($_POST['session_token']) && isset($_POST['id'])) {
            $idUser = processToken($_POST['session_token']);
            $vote = VoteCRUD::select($_POST['id']);
            if ($vote) {
                $arrayVote = $vote -> getAttributes();
                if ( CommunityCRUD::isAdmin($idUser,$arrayVote['id_community']) === 1 ) {
                    sendJsonSucess(VoteCRUD::remove($arrayVote['id']));
                }
            }
        }
    }

    remove();
?>