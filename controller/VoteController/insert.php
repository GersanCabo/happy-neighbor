<?php 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    require_once("../../model/class/Vote.php");
    require_once("../../model/crud/VoteCRUD.php");
    require_once("../../model/class/Community.php");
    require_once("../../model/crud/CommunityCRUD.php");
    require_once("../Utilities.php");

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

    insert();
?>
