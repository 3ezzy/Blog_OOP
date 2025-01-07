<?php 

    class Database {
        private $dsn = "mysql:host=localhost;dbname=blog_poo";
        private $username = "root";
        private $password = "123";
        public $conn;
    
    
        public function query($sql) {
            return $this->conn->query($sql);
        }
    
        
        public function prepare($sql) {
            return $this->conn->prepare($sql);
        }
    
        
    }
?>