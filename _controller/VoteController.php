<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../model/class/Vote.php");
    require_once("../model/crud/VoteCRUD.php");
    require_once("../model/class/Community.php");
    require_once("../model/crud/CommunityCRUD.php");
    require_once("Utilities.php");

    /**
     * Insert a vote in the table
     */
    function insert() {
        if (isset($_POST['session_token']) && isset($_POST['id_community'])) {
            $idUser = processToken($_POST['session_token']);
            if (CommunityCRUD::isAdmin($idUser,$_POST['id_community']) === 1) {
                $dateEnd = null;
                if ($_POST['date_end']) {
                    $dateEnd = $_POST['date_end '];
                }
                $vote = new Vote(
                    1,
                    $_POST['name_vote'],
                    $_POST['description_vote'],
                    $idUser,
                    $_POST['id_community'],
                    $dateEnd
                );
                sendJsonSucess(VoteCRUD::insert($vote));
            }
        }
    }

    /**
     * Update end date of the vote
     */
    function updateDateEnd() {
        if (isset($_POST['session_token']) && isset($_POST['id'])) {
            $idUser = processToken($_POST['session_token']);
            $vote = VoteCRUD::select($_POST['id']);
            if ($vote) {
                $arrayVote = $vote -> getAttributes();
                if (CommunityCRUD::isAdmin($idUser,$arrayVote['id_community']) === 1) {
                    $dateEnd = null;
                    if ($_POST['date_end']) {
                        $dateEnd = $_POST['date_end'];
                    }
                    sendJsonSucess(VoteCRUD::updateDateEnd($dateEnd,$_POST['id']));
                }
            }
        }
    }

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
                if ( gettype(CommunityCRUD::isAdmin($idUser,$arrayVote['id_community'])) == "integer" && CommunityCRUD::selectPermissionUser($idUser,$arrayVote['id_community']) 
                        && ($arrayVote['date_end'] == null || ( strtotime($arrayVote['date_end']) - time() ) > 0 ) && !(VoteCRUD::checkVoteUser($idUser,$arrayVote['id'])) ) {
                    sendJsonSucess(VoteCRUD::vote( $_POST['id'],$idUser,boolval($_POST['vote_value']) ));
                }
            }
        }
    }

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

    /**
     * Choose a action according to the value action in $_POST
     */
    function runActions() {
        if ( isset($_POST['action']) ) {
            if ($_POST['action'] == "insert") {
                insert();
            } else if ($_POST['action'] == "updateDateEnd") {
                updateDateEnd();
            } elseif ($_POST['action'] == "vote") {
                vote();
            } else if ($_POST['action'] == "remove") {
                remove();
            }
        }
    }

    runActions();
?>