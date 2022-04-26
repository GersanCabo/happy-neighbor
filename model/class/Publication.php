<?php 
    require_once(realpath($_SERVER['DOCUMENT_ROOT']).'/PHP/happy_neighbor/model/class/Utilities.php');

    class Publication {

        const REG_EXPR_STRING_WITH_SPACES = '/^[\w]+( [\w]+)*$/';
        const STRINGS_TO_VERIFY = [
            'text_publication' => [
                '/.+/im',
                300,
                1
            ]
        ];

        private array $attributes = [];

        public function __construct(int $id, $textPublication, $idUser, $idCommunity, $commentTo=null, $likes=0, $datePublication=null) {
            if ($id > 0 && strlen($textPublication) > 0 && Utilities::validateString(
                    [$textPublication, self::STRINGS_TO_VERIFY['text_publication'][0], self::STRINGS_TO_VERIFY['text_publication'][1], self::STRINGS_TO_VERIFY['text_publication'][2]]
                )) {
                    if ($commentTo == "" || $commentTo <= 0) {
                        $commentTo = null;
                    }
                    $this->attributes['id'] = $id;
                    $this->attributes['text_publication'] = $textPublication;
                    $this->attributes['id_user'] = $idUser;
                    $this->attributes['id_community'] = $idCommunity;
                    $this->attributes['comment_to'] = $commentTo;
                    $this->attributes['likes'] = $likes;
                    $this->attributes['date_publication'] = $datePublication;
                }
        }

        /**
         * Convert array to publication object
         * 
         * @param array $publication array with publication parammeters
         * @return object object publication 
         */
        public static function getpublication(array $publication) {
            return new Publication($publication['id'], $publication['text_publication'], $publication['id_user'], $publication['id_community'], $publication['comment_to'], $publication['likes'], $publication['date_publication']);
        }

        /**
         * Return attributes of publication object
         * 
         * @return array publication attributes
         */
        public function getAttributes() {
            return $this->attributes;
        }

        /**
         * Publication class getter
         *
         * @param string $attribute attribute name
         * @return mixed attribute value
         */
        public function __get(string $attribute) {
            return $this->attributes[$attribute];
        }

        /**
         * Publication class setter
         *
         * @param string $attribute attribute name
         * @param mixed $value attribute value
         */
        public function __set(string $attribute, $value) {
            if(Utilities::validateSetter($attribute, $value, self::STRINGS_TO_VERIFY)) {
                $this->attributes[$attribute] = $value;
            }
        }

    }
?>