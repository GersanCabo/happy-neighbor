<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/Vote.php");
    require_once("../../model/crud/VoteCRUD.php");
    require_once("../../model/class/Community.php");
    require_once("../../model/crud/CommunityCRUD.php");
    require_once("../Utilities.php");

    /**
     * Add a user vote
     */
    function vote() {
        if (isset($_POST['session_token']) && isset($_POST['id']) && isset($_POST['vote_value'])) {
            $idUser = processToken($_POST['session_token']);
            $vote = VoteCRUD::select($_POST['id']);
            if ($vote) {
                $arrayVote = $vote -> getAttributes();
                /*
                    If validations:
                        1. If the user is a member of the community
                        2. If the user has permission to vote
                        3. If the vote has not expired
                        4. If the user has not voted before
                */
                if ( gettype(CommunityCRUD::isAdmin($idUser,$arrayVote['id_community'])) == "integer" 
                        && CommunityCRUD::selectPermissionUser($idUser,$arrayVote['id_community']) 
                        && ($arrayVote['date_end'] == null || ( strtotime($arrayVote['date_end']) - time() ) > 0 ) 
                        && !(VoteCRUD::checkVoteUser($idUser,$arrayVote['id'])) ) {
                    sendJsonSucess(VoteCRUD::vote( $_POST['id'],$idUser,boolval($_POST['vote_value']) ));
                }
            }
        }
    }

    vote();
?>