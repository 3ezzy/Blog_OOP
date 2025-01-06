<?php 

class Authentication {

    private $db;
    public function __construct($db) {
        $this->db = $db;
        session_start(); 
    }

    public function login($username, $password) {
        $sql = "SELECT * FROM user WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password if user exists
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header('Location: index.php');
            exit();
        } else {
            echo "Invalid username or password";
        }
    }

    public function logout() {
        unset($_SESSION['user']);
        header('Location: login.php');
        exit();
    }

    public function verifierSession() {
        if (!isset($_SESSION['user'])) {
            header('Location: login.php');
            exit();
        }
    }

    public function signup($username, $email, $password) {
        // Check if the username or email already exists
        $sql = "SELECT * FROM user WHERE email = :email OR username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return false; // User already exists
        }

        // Hash the password and insert the new user
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $role_id = 2; // Default user role

        $sql = "INSERT INTO user (username, email, password, role_id) VALUES (:username, :email, :password, :role_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role_id', $role_id);
        return $stmt->execute();
    }
}
?>
