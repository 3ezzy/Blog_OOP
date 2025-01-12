<?php


require_once 'User.php';

class Member extends User {
    public function register(string $username, string $password, string $email): array {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $this->db->prepare("INSERT INTO users (username, password, email, role_id) VALUES (:username, :password, :email, 2)");
            $stmt->execute([
                'username' => $username,
                'password' => $hashedPassword,
                'email' => $email
            ]);

            return ['status' => 'success', 'message' => 'User registered successfully'];
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') { 
                return ['status' => 'error', 'message' => 'email already exists'];
            }
            return ['status' => 'error', 'message' => 'Failed to register user: ' . $e->getMessage()];
        }
    }
}
?>
