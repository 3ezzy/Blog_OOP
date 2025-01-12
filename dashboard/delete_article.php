<?php
session_start();
include "../classes/Article.php";
include "../db/Database.php";

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/register&login.php');
    exit();
}

$userId = $_SESSION['user_id']; // Logged-in user's ID
$articleId = $_POST['id']; // Article ID to delete

// Initialize database and Article class
$db = (new Database())->connect();
$articleObj = new Article($db);

// Attempt to delete the article
$response = $articleObj->delete($articleId, $userId);

if ($response['status'] === 'success') {
    header('Location: article.php?message=Article deleted successfully');
} else {
    header('Location: article.php?error=' . urlencode($response['message']));
}
