<?php 
    class DB {
        private static $connection = NULL;
        private function __construct() {}

        public static function connect() {
            try {
                [$host, $user, $passwd, $db] = ['localhost', 'happyneighbor', '5GLJyHW8q6Oa', 'happyneighbor'];
                self::$connection = new PDO("mysql:host=$host;dbname=$db;charset=utf8",$user,$passwd);
                return self::$connection;
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        }
    }
?>