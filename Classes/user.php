<?php

class User {
    protected $db;
    
    protected $secret;

    public function __construct(PDO $db, string $secret = '') {
        $this->db = $db;
        $this->secret = $secret;
    }

    public function login(string $email, string $password): array {
        session_start();
    
        // Fetch user and role from the database
        $stmt = $this->db->prepare("
            SELECT u.*, r.name as 'role_name' 
            FROM users u 
            JOIN roles r ON u.role_id = r.id 
            WHERE email = :email
        ");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user && password_verify($password, $user['password'])) {

    
            // Store user ID and role in the session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role_name'];
    
            return ['status' => 'success', 'message' => 'Logged in successfully'];
        } else {
            return ['status' => 'error', 'message' => 'Invalid email or password'];
        }
    }
    

    public function logout(): array {
        // Invalidate the session and cookie
        session_destroy();
        setcookie('auth_token', '', time() - 3600, '/', '', false, true);
        return ['status' => 'success', 'message' => 'Logged out successfully'];
    }

}

