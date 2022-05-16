<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/Vote.php");
    require_once("../../model/crud/VoteCRUD.php");
    require_once("../../model/class/Community.php");
    require_once("../../model/crud/CommunityCRUD.php");
    require_once("../Utilities.php");

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

    updateDateEnd();
?>