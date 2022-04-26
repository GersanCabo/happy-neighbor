<?php 
    require_once(realpath($_SERVER['DOCUMENT_ROOT']).'/PHP/happy_neighbor/model/Connection.php');

    class PublicationCRUD {

        private const NOT_NULL_PARAMETERS = [
            'text_publication',
            'id_user',
            'id_community'
        ];

        public function __construct() {}

        /**
         * Insert a publication in the table and return the result
         * 
         * @param object publication object
         * @return bool if the publication is inserted or not
         */
        public static function insert(Publication $publication):bool {
            $result = false;
            $db = Db::connect();
            $isPossibleInsertPublication = true;
            $attributes = $publication -> getAttributes();
            foreach (self::NOT_NULL_PARAMETERS as $parameters) {
                if ($attributes[$parameters] == null || $attributes[$parameters] == "") {
                    $isPossibleInsertPublication = false;
                    break;
                }
            }
            if ($isPossibleInsertPublication) {
                $commentTo = $attributes['comment_to'];
                if ($commentTo == null || $commentTo == "" || $commentTo <= 0) {
                    $commentTo = "null";
                }   
                $insertSentence = $db -> prepare("INSERT INTO publication VALUES(null,'" . $attributes['text_publication'] . "',0,null," . $commentTo . "," . $attributes['id_user'] . "," . $attributes['id_community'] . ");");
                $result = $insertSentence->execute();
            }
            return $result;
        }

        public static function select(int $id) {
            $publication = false;
            $db = Db::connect();
            $result = $db -> query("SELECT * FROM publication WHERE id=$id");
            if ($arrayPublication = $result -> fetch(PDO::FETCH_ASSOC)) {
                $publication = Publication::getPublication($arrayPublication);
            }
            return $publication;
        }

        /**
         * Check if a user has already liked a publication
         * 
         * @param int $idUser user id
         * @param int $idPublication publication id
         * @return bool $likedPublication if a user has liked a publication or not
         */
        public static function checkLikeUser(int $idUser, int $idPublication):bool {
            $likedPublication = false;
            $db = Db::connect();
            $result = $db -> query("SELECT * FROM user_like WHERE id_user=$idUser AND id_publication=$idPublication");
            if ($result -> fetch(PDO::FETCH_ASSOC)) {
                $likedPublication = true;
            }
            return $likedPublication;
        }

        public static function addLike(int $id, int $idUser) {
            $result = false;
            $db = DB::connect();
            $existPublication = self::select($id);
            if ($existPublication) {
                $insertSentence = $db -> prepare("INSERT INTO user_like VALUES ($idUser,$id);");
                $result = $insertSentence->execute();
            }
            return $result;
        }

        public static function removeLike(int $id, int $idUser) {
            $result = false;
            $db = DB::connect();
            $existPublication = self::select($id);
            if ($existPublication) {
                $insertSentence = $db -> prepare("DELETE FROM user_like WHERE id_user=$idUser AND id_publication=$id;");
                $result = $insertSentence->execute();
            }
            return $result;
        }
    }
?>