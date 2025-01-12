<?php
session_start();
include "../db/Database.php";
include "../classes/Article.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure the user is logged in
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        die(json_encode(['status' => 'error', 'message' => 'You must be logged in to add an article']));
    }


    // Get user input
    $title = $_POST['title'] ?? null;
    $content = $_POST['content'] ?? null;
    $categoryId = $_POST['category_id'] ?? null;
    $imageFile = $_FILES['image'] ?? null;

    if (!$title || !$content || !$categoryId || !$imageFile) {
        die(json_encode(['status' => 'error', 'message' => 'All fields are required']));
    }

    try {
        // Initialize database and Article class
        $db = (new Database())->connect();
        $article = new Article($db);

        // Add the article
        $response = $article->create($title, $content, $imageFile, $_SESSION['user_id'], (int)$categoryId);

        // Redirect back to the articles dashboard
        if ($response['status'] === 'success') {
            header('Location: article.php');
            exit();
        } else {
            die(json_encode($response));
        }
    } catch (Exception $e) {
        die(json_encode(['status' => 'error', 'message' => 'An unexpected error occurred: ' . $e->getMessage()]));
    }
}
