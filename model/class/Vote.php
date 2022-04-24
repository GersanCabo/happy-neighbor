<?php 
    require_once(realpath($_SERVER['DOCUMENT_ROOT']).'/PHP/happy_neighbor/model/class/Utilities.php');

    class Vote {

        const REG_EXPR_STRING_WITH_SPACES = '/^[\w]+( [\w]+)*$/';
        const STRINGS_TO_VERIFY = [
            'name_vote' => [
                self::REG_EXPR_STRING_WITH_SPACES,
                50,
                1
            ],
            'description_vote' => [
                '/.+/im',
                300,
                0
            ]
        ];

        private array $attributes = [];

        public function __construct(int $id, $nameVote, $descriptionVote, $userCreator, $idCommunity, $dateEnd = null, $positiveVotes = null, $negativeVotes = null, $dateStart = null) {
            if ($id > 0 && ($userCreator > 0 || $userCreator == null) && ($idCommunity > 0 || $idCommunity == null) && ($dateEnd == null || $this->validateDateEnd($dateEnd)) && Utilities::validateString(
                    [$nameVote, self::STRINGS_TO_VERIFY['name_vote'][0], self::STRINGS_TO_VERIFY['name_vote'][1], self::STRINGS_TO_VERIFY['name_vote'][2]],
                    [$descriptionVote, self::STRINGS_TO_VERIFY['description_vote'][0], self::STRINGS_TO_VERIFY['description_vote'][1], self::STRINGS_TO_VERIFY['description_vote'][2]]
            )) {
                    $this->attributes['id'] = $id;
                    $this->attributes['name_vote'] = $nameVote;
                    $this->attributes['description_vote'] = $descriptionVote;
                    $this->attributes['user_creator'] = $userCreator;
                    $this->attributes['id_community'] = $idCommunity;
                    $this->attributes['positive_votes'] = $positiveVotes;
                    $this->attributes['negative_votes'] = $negativeVotes;
                    $this->attributes['date_start'] = $dateStart;
                    $this->attributes['date_end'] = $dateEnd;
                }
        }

        /**
         * Convert array to vote object
         * 
         * @param array $vote array with vote parammeters
         * @return object object vote 
         */
        public static function getVote(array $vote) {
            return new Vote($vote['id'], $vote['name_vote'], $vote['description_vote'], $vote['user_creator'], $vote['id_community'], $vote['date_end'], $vote['positive_votes'], $vote['negative_votes'], $vote['date_start']);
        }

        /**
         * Return attributes of vote object
         * 
         * @return array vote attributes
         */
        public function getAttributes() {
            return $this->attributes;
        }

        /**
         * Vote class getter
         *
         * @param string $attribute attribute name
         * @return mixed attribute value
         */
        public function __get(string $attribute) {
            return $this->attributes[$attribute];
        }

        /**
         * Vote class setter
         *
         * @param string $attribute attribute name
         * @param mixed $value attribute value
         */
        public function __set(string $attribute, $value) {
            if(Utilities::validateSetter($attribute, $value, self::STRINGS_TO_VERIFY) || ( $attribute == "date_end" && ($value == null || $this->validateDateEnd($value)) )) {
                $this->attributes[$attribute] = $value;
            }
        }

        private function validateDateEnd(string $dateEnd) {
            $isValidate = false;
            if(preg_match('/[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]/',$dateEnd)) {
                $timePassed = strtotime($dateEnd) - time();
                if ($timePassed > 0) {
                    $isValidate = true;
                }
            }
            return $isValidate;
        }

    }
?>