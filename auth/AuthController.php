<?php
require 'JWTGenerator.php';
require 'JWTVerifier.php';

class AuthController {
    private $db;
    private $jwtGenerator;
    private $jwtVerifier;
    private $secret;

    public function __construct(PDO $db, string $secret = '') {
        $this->db = $db;
        $this->jwtGenerator = new JWTGenerator($secret);
        $this->jwtVerifier = new JWTVerifier($secret);
        $this->secret = $secret;
    }
    public function register(string $username, string $password, string $email): array {
        try {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
            // Insert user into database
            $stmt = $this->db->prepare("INSERT INTO users (username, password, email, role_id) VALUES (:username, :password, :email, 2)");
            $stmt->execute([
                'username' => $username,
                'password' => $hashedPassword,
                'email' => $email
            ]);
    
            return ['status' => 'success', 'message' => 'User registered successfully'];
        } catch (PDOException $e) {
            // Handle specific SQL error codes (e.g., unique constraint violation)
            if ($e->getCode() === '23000') { // SQLSTATE 23000: Integrity constraint violation
                return ['status' => 'error', 'message' => 'email already exists'];
            }
            // General error
            return ['status' => 'error', 'message' => 'Failed to register user: ' . $e->getMessage()];
        }
    }
    

    public function login(string $email, string $password): array {
        // Fetch user from database
        
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Generate JWT
            $header = ['alg' => 'HS256', 'typ' => 'JWT'];
            $payload = [
                'username' => $user['username'],
                'iat' => time(),
                'exp' => time() + 3600 // Token expires in 1 hour
            ];
            $jwt = $this->jwtGenerator->create($header, $payload);

            // Set JWT in a cookie
            setcookie('auth_token', $jwt, time() + 3600, '/', '', false, true); // HttpOnly cookie
            return ['status' => 'success', 'message' => 'Logged in successfully'];
        } else {
            return ['status' => 'error', 'message' => 'Invalid email or password'];
        }
    }

    public function logout(): array {
        // Invalidate the cookie
        setcookie('auth_token', '', time() - 3600, '/', '', false, true);
        return ['status' => 'success', 'message' => 'Logged out successfully'];
    }

    public function verify(): array {
        // Verify JWT from cookie
        if (isset($_COOKIE['auth_token'])) {
            $jwt = $_COOKIE['auth_token'];
            $payload = $this->jwtVerifier->verify($jwt);

            if ($payload) {
                return ['status' => 'success', 'user' => $payload];
            }
        }
        return ['status' => 'error', 'message' => 'Invalid or expired token'];
    }
}


require_once("../db/database.php");

$conn = new Database();
$db = $conn->connect();
// header("content-type: application/json");
// echo json_encode($conn);
// Secret key for JWT
$secret = 'rida';

// Create an AuthController instance
$auth = new AuthController($db );

// // Example routes
// $action = $_GET['action'] ?? null;

// if ($action === 'register') {
//     $response = $auth->register($_POST['username'], $_POST['password']);
// } elseif ($action === 'login') {
//     $response = $auth->login($_POST['username'], $_POST['password']);
// } elseif ($action === 'logout') {
//     $response = $auth->logout();
// } elseif ($action === 'verify') {
//     $response = $auth->verify();
// } else {
//     $response = ['status' => 'error', 'message' => 'Invalid action'];
// }

// header('Content-Type: application/json');
// echo json_encode($response);

// $response = $auth->register("rida chaanounqq", "123456789",'ridchaanoun11qq@aagmaiqqaaaql.com');
$response = $auth->login('ridchaanoun11qq@gmaiqqaaaql.com', "123456789");

header('Content-Type: application/json');
echo json_encode($response);