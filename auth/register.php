<?php
require_once '../db/database.php';
require_once '../classes/Member.php';
require_once '../validators/Validator.php';

session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Initialize Validator and validate inputs
    $validator = new Validator();
    $validator->validateUsername($name);
    $validator->validateEmail($email);
    $validator->validatePassword($password);

    if ($validator->isValid()) {
        // Initialize Member class
        $db = (new Database())->connect();
        $member = new Member($db);

        // Register user
        $result = $member->register($name, $email, $password);

        if ($result['status'] === 'success') {
            // Redirect to login page with success message
            header('Location: ../pages/register&login.php');
        } else {
            // Redirect to registration form with registration error message
            $result ["from"] = "Registration";
            $queryString = http_build_query(['errors' => $result]);
            header('Location: ../pages/register&login.php?'. $queryString );
        }
    } else {
        // Get validation errors and redirect back with error messages
        $errors = $validator->getErrors();
        $errors ["from"] = "Registration";
        $queryString = http_build_query(["errors" => $errors]);
        header('Location: ../pages/register&login.php?' . $queryString);
    }
    exit();
}
