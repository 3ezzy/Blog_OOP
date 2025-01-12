<?php
session_start();
require_once '../db/database.php'; 
require_once '../classes/User.php';
require_once '../validators/Validator.php';
$db = (new database )->connect();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize variables
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Initialize the Validator class
    $validator = new Validator();
    $validator->validateEmail($email);
    $validator->validatePassword($password);

    // Check if there are validation errors
    if (!$validator->isValid()) {
        // If validation fails, redirect to the login page with errors
        $queryString = http_build_query([
            'errors' => $validator->getErrors(),
        ]);
        header("Location: ../pages/register&login.php?$queryString");
        exit();
    }

    // Initialize the User class
    $user = new User($db); // Assumes $db is the database connection

    // Attempt login
    $loginResult = $user->login($email, $password);

    if ($loginResult['status'] === 'success') {
        // Login successful, redirect to the user dashboard or another page
        // $_SESSION['user_id'] = $loginResult['user_id']; // Store user info in session
        // $_SESSION['username'] = $loginResult['username'];
        header('Location: ../index.php'); // Redirect to a protected page
        exit();
    } else {
        // If login fails, redirect to the login page with error message
        $queryString = http_build_query([
            'errors' => ['login' => 'Invalid email or password.'],
        ]);
        header("Location: ../pages/register&login.php?$queryString");
        exit();
    }
}
?>
