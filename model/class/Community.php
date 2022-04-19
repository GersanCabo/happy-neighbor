<?php 
    require_once(realpath($_SERVER['DOCUMENT_ROOT']).'/PHP/happy_neighbor/model/class/Utilities.php');

    class Community {

        const REG_EXPR_STRING_WITH_SPACES = '/^[\w]+( [\w]+)*$/';
        const STRINGS_TO_VERIFY = [
            'name_community' => [
                self::REG_EXPR_STRING_WITH_SPACES,
                50,
                1
            ],
            'description_community' => [
                '/.+/im',
                300,
                0
            ]
        ];

        private array $attributes = [];

        public function __construct(int $id, $name_community, $description_community, $total_money, $user_creator_id = null, $creation_date = null) {
            if ($total_money == null || $total_money == "") {
                $total_money = 0.0;
            }
            if ($id > 0 && ($user_creator_id > 0 || $user_creator_id == null) && Utilities::validateString(
                    [$name_community, self::STRINGS_TO_VERIFY['name_community'][0], self::STRINGS_TO_VERIFY['name_community'][1], self::STRINGS_TO_VERIFY['name_community'][2]],
                    [$description_community, self::STRINGS_TO_VERIFY['description_community'][0], self::STRINGS_TO_VERIFY['description_community'][1], self::STRINGS_TO_VERIFY['description_community'][2]]
                )) {
                    $this->attributes['id'] = $id;
                    $this->attributes['name_community'] = $name_community;
                    $this->attributes['description_community'] = $description_community;
                    $this->attributes['total_money'] = floatval($total_money);
                    $this->attributes['user_creator_id'] = $user_creator_id;
                    $this->attributes['creation_date'] = $creation_date;
                }
        }

        /**
         * Convert array to community object
         * 
         * @param array $community array with community parammeters
         * @return object object community 
         */
        public static function getCommunity(array $community) {
            return new Community($community['id'], $community['name_community'], $community['description_community'], $community['total_money'], $community['user_creator_id'], $community['creation_date']);
        }

        /**
         * Return attributes of community object
         * 
         * @return array community attributes
         */
        public function getAttributes() {
            return $this->attributes;
        }

        /**
         * Community class getter
         *
         * @param string $attribute attribute name
         * @return mixed attribute value
         */
        public function __get(string $attribute) {
            return $this->attributes[$attribute];
        }

        /**
         * User class setter
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