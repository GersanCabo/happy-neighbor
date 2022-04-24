<?php 
    require_once("../model/class/Vote.php");
    require_once("../model/crud/VoteCRUD.php");
    require_once("../model/class/Community.php");
    require_once("../model/crud/CommunityCRUD.php");
    require_once("Utilities.php");

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
     * Insert a vote in the table
     */
    function insert() {
        if (isset($_POST['session_token']) && isset($_POST['id_community'])) {
            $idUser = processToken($_POST['session_token']);
            if (CommunityCRUD::isAdmin($idUser,$_POST['id_community']) == "1") {
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

    function updateDateEnd() {
        if (isset($_POST['session_token']) && isset($_POST['id'])) {
            $idUser = processToken($_POST['session_token']);
            $vote = VoteCRUD::select($_POST['id']);
            if ($vote) {
                $arrayVote = $vote -> getAttributes();
                if (CommunityCRUD::isAdmin($idUser,$arrayVote['id_community']) == "1") {
                    $dateEnd = null;
                    if ($_POST['date_end']) {
                        $dateEnd = $_POST['date_end'];
                    }
                    sendJsonSucess(VoteCRUD::updateDateEnd($dateEnd,$_POST['id']));
                }
            }
        }
    }

    function vote() {
        
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
            }
        }
    }

    runActions();
?>