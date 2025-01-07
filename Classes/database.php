<?php 

    class Database {
        private $dsn = "mysql:host=localhost;dbname=blog_poo";
        private $username = "root";
        private $password = "123";
        public $conn;
    
        // Constructor to establish a database connection using PDO
        public function __construct() {
            try {
                $this->conn = new PDO($this->dsn, $this->username, $this->password);
                // Set the PDO error mode to exception
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo "True";
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
    
        public function query($sql) {
            return $this->conn->query($sql);
        }
    
        
        public function prepare($sql) {
            return $this->conn->prepare($sql);
        }
    
        
    }
?>