<?php
require_once(LIB_PATH.DS."config.php");

    class MySQLDatabase {

        private $connection;
        private $rowCount;
        // Connect to database by default upon construction
        function __construct() {
            $this->open_connection();
        }

        // 1. Create database connection; I am using prepared statements (PDO- PHP Data Object)
        public function open_connection() {
            try{
        		$conn_string = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=utf8";
        		$this->connection = new PDO($conn_string, DB_USER, DB_PASS);
        		$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        	} catch (PDOException $e) {
        		echo "Connection failed: " . $e->getMessage();
        	}
        }

        public function close_connection() {
            $this->connection = null;
        }

        // This function works for select queries
        public function query($stmt, $params = []){
            // Prepare statement to avoid all SQL injection
			$query = $this->connection->prepare($stmt);
			$query->execute($params);
            $result = $query->fetchAll();
            $this->rowCount = $query->rowCount();
            return $result;
        }

        // Alternative query for CUD (no R), since fetchAll doesn't work for INSERT/UPDATE
        public function cud_query($stmt, $params = []){
            // Prepare statement to avoid all SQL injection
			$query = $this->connection->prepare($stmt);
			$result = $query->execute($params);
            $this->rowCount = $query->rowCount();
            return $result;
        }

        // "Database neutral" functions

        public function num_rows($result_set){
            return sizeof($result_set);
        }

        public function insert_id() {
            return $this->connection->lastInsertId();
        }

        public function affected_rows(){
            return $this->rowCount;
        }
    }

    $database = new MySQLDatabase();

?>
