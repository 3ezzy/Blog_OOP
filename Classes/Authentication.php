<?php 

class Authentication {

    private $db;
    public function __construct($db) {
        $this->db = $db;
    }

    public function login($username, $password) {
        $sql = "SELECT * FROM user WHERE username = :username AND password = :password";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username, 'password' => $password]);
        $user = $stmt->fetch();
        if ($user) {
            $_SESSION['user'] = $user;
            header('Location: index.php');
        } else {
            echo "Invalid username or password";
        }
    }

    public function logout() {
        unset($_SESSION['user']);
        header('Location: login.php');
    }





}

?>