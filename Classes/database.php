<?php

class Database
{
    private $servername = "localhost";
    private $username = "root";
    private $password = "123";
    private $dbName = "blog_poo";
    public $conn;



    public function getConnection()
    {
        $this->conn = null;


        try {
            $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbName);
            if ($this->conn) {
                return $this->conn;
                // throw new Exception("Connection failed: " . $this->conn->connect_error);
            }
        } catch (Exception $e) {
            echo "Connection error: " . $e->getMessage();
        }

        return $this->conn;
    }
}
