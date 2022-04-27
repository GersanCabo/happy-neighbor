<?php 
    require_once(realpath($_SERVER['DOCUMENT_ROOT']).'/PHP/happy_neighbor/model/Connection.php');

    class VoteCRUD {

        private const NOT_NULL_PARAMETERS = [
            'name_vote',
            'user_creator',
            'id_community'
        ];

        public function __construct() {}

        /**
         * Insert a vote in the table and return the result
         * 
         * @param object vote object
         * @return bool if the vote is inserted or not
         */
        public static function insert(Vote $vote):bool {
            $result = false;
            $db = Db::connect();
            $isPossibleInsertVote = true;
            $attributes = $vote -> getAttributes();
            foreach (self::NOT_NULL_PARAMETERS as $parameters) {
                if ($attributes[$parameters] == null || $attributes[$parameters] == "") {
                    $isPossibleInsertVote = false;
                    break;
                }
            }
            if ($isPossibleInsertVote) {
                $dateEnd = $attributes['date_end'];
                if ($attributes['date_end'] == null || $attributes['date_end'] == "") {
                    $dateEnd = "null";
                }
                $insertSentence = $db -> prepare("INSERT INTO vote VALUES(null,'" . $attributes['name_vote'] . "','" . $attributes['description_vote'] . "',0,0,null," . $dateEnd . "," . $attributes['user_creator'] . "," . $attributes['id_community'] . ");");
                $result = $insertSentence->execute();
            }
            return $result;
        }

        /**
         * Update a date end of a vote from the table and return the result
         * 
         * @param object vote object
         * @return bool if the vote is updated or not
         */
        public static function updateDateEnd(string $dateEnd, int $id):bool {
            $result = false;
            $db = Db::connect();
            $vote = self::select($id);
            if ($vote) {
                $vote -> __set('date_end',$dateEnd);
                $attributes = $vote -> getAttributes();
                if ($attributes['date_end'] == $dateEnd) {
                    $updateSentence = $db -> prepare("UPDATE vote SET date_end='$dateEnd' WHERE id=$id");
                    $result = $updateSentence->execute();
                }
            }
            return $result;
        }

        /**
         * Search a vote in the table and return it if found
         * 
         * @param int $id vote id (Primary key)
         * @return $vote object Vote or false if not located 
         */
        public static function select(int $id) {
            $vote = false;
            $db = Db::connect();
            $result = $db -> query("SELECT * FROM vote WHERE id=$id");
            if ($arrayVote = $result -> fetch(PDO::FETCH_ASSOC)) {
                $vote = Vote::getVote($arrayVote);
            }
            return $vote;
        }

        /**
         * Remove a vote from the table
         * 
         * @param int $id vote id
         * @return bool if the vote is removed or not
         */
        public static function remove(int $id):bool {
            $result = false;
            $db = Db::connect();
            $vote = self::select($id);
            if ($vote) {
                $removeSentence = $db -> prepare("DELETE FROM vote WHERE id=$id");
                $result = $removeSentence->execute();
            }
            return $result;
        }

        /**
         * Check if a user has already voted
         * 
         * @param int $idUser user id
         * @param int $idVote vote id
         * @return bool $vote if a user has voted or not
         */
        public static function checkVoteUser(int $idUser, int $idVote):bool {
            $vote = false;
            $db = Db::connect();
            $result = $db -> query("SELECT * FROM user_vote WHERE id_user=$idUser AND id_vote=$idVote");
            if ($result -> fetch(PDO::FETCH_ASSOC)) {
                $vote = true;
            }
            return $vote;
        }

        /**
         * Add a user's vote
         * 
         * @param int $id vote id
         * @param int $idUser user id
         * @param bool $voteVal user's vote value (yes or not)
         * @return bool $result if the user's vote has been recorded or not
         */
        public static function vote(int $id, int $idUser, bool $voteVal):bool {
            $result = false;
            $db = DB::connect();
            $existVote = self::select($id);
            if ($existVote) {
                $voteString = "false";
                if ($voteVal) {
                    $voteString = "true";
                }
                $insertSentence = $db -> prepare("INSERT INTO user_vote VALUES ($idUser,$id,$voteString);");
                $result = $insertSentence->execute();
            }
            return $result;
        }
    }
?>