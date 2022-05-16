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

        /**
         * Select a publication in the table and return the result
         * 
         * @param int $id publication id
         * @return $publication object publication or false
         */
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
         * Remove a publication in the table and return the result
         * 
         * @param $id publication id
         * @return bool $result if the publication is removed or not
         */
        public static function remove(int $id):bool {
            $result = false;
            $db = Db::connect();
            $existPublication = self::select($id);
            if ($existPublication) {
                $insertSentence = $db -> prepare("DELETE FROM publication WHERE id=$id");
                $result = $insertSentence->execute();
            }
            return $result;
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

        /**
         * Add a like in a publication
         * 
         * @param int $id publication id
         * @param int $idUser user id
         * @return bool $result like is added or not
         */
        public static function addLike(int $id, int $idUser):bool {
            $result = false;
            $db = DB::connect();
            $existPublication = self::select($id);
            if ($existPublication) {
                $insertSentence = $db -> prepare("INSERT INTO user_like VALUES ($idUser,$id);");
                $result = $insertSentence->execute();
            }
            return $result;
        }

        /**
         * Remove a like of a publication
         * 
         * @param int $id publication id
         * @param int $idUser user id
         * @return bool $result like is removed or not    
         */
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

        /**
         * Select all publications of a community
         * 
         * @param int $idCommunity community id
         * @param int $numPage start page num
         * @return array $publications list of community publications
         */
        public static function selectCommunityPublications(int $idCommunity, int $numPage) {
            $publications = [];
            $db = Db::connect();
            $isLastPage = false;
            $max = self::countCommunityPublications($idCommunity);
            $startPage = 0;
            if ($numPage > 0) {
                $startPage = $numPage * 15;
            }
            $endPage = $startPage + 15;
            if ($endPage >= $max) {
                $endPage = $max;
                $isLastPage = true;
            } 
            $result = $db -> query("SELECT * FROM publication WHERE id_community=$idCommunity ORDER BY date_publication LIMIT $startPage, $endPage");
            while ($arrayPublication = $result -> fetch(PDO::FETCH_ASSOC)) {
                $publication = Publication::getPublication($arrayPublication);
                $publications[] = $publication->getAttributes();
            }
            return [$publications,$isLastPage];
        }

        /**
         * Count all publications of a community
         */
        public static function countCommunityPublications(int $idCommunity) {
            $countPublications = 0;
            $db = DB::connect();
            $result = $db -> query("SELECT COUNT(*) FROM publication WHERE id_community=$idCommunity");
            if ($countArray = $result -> fetch(PDO::FETCH_ASSOC)) {
                $countPublications = intval($countArray["COUNT(*)"]);
            }
            return $countPublications;
        } 
    }
?>