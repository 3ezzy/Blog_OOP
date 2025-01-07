<?php
class User {
    private $db;
    public $id;
    public $username;
    public $email;
    public $role_id;

    

    // Load a user by their id
    public function loadUserById($id) {
        $sql = "SELECT * FROM user WHERE id = :id"; // Changed to 'user'
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            $this->id = $result['id'];
            $this->username = $result['username'];
            $this->email = $result['email'];
            $this->role_id = $result['role_id'];
            
        } else {
            throw new Exception("User not found.");
        }
    }

    // Update the role of a user
    public function updateUserRole($role_id) {
        $sql = "UPDATE user SET role_id = :role_id WHERE id = :id"; 
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':role_id', $role_id);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    // Check if the user can log in
    public function canLogin($usernameOrEmail, $password) {
        $sql = "SELECT * FROM user WHERE username = :usernameOrEmail OR email = :usernameOrEmail";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':usernameOrEmail', $usernameOrEmail);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            return true;
        }
        return false;
    }

    // User logout
    public function logout() {
        session_unset();
        session_destroy();
    }
}
?>
