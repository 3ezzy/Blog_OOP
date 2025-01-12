<?php
session_start();
include "../db/Database.php";
include "../classes/Article.php";
include "../classes/Category.php";

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/register&login.php');
    exit();
}

// Initialize database connection
$db = (new Database())->connect();

// Initialize Article and Category classes
$articleObj = new Article($db);
$categoryObj = new Category($db);

// Fetch the article to update
if (!isset($_GET['id'])) {
    echo "Article ID is missing!";
    exit();
}

$articleId = (int) $_GET['id'];
$articleResponse = $articleObj->getUserArticles($_SESSION['user_id']);
$articles = $articleResponse['status'] === 'success' ? $articleResponse['data'] : [];
$article = null;

foreach ($articles as $a) {
    if ($a['id'] === $articleId) {
        $article = $a;
        break;
    }
}

if (!$article) {
    echo "Article not found or you do not have permission to update it.";
    exit();
}

// Fetch all categories
$categoriesResponse = $categoryObj->listCategories();
$categories = $categoriesResponse;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $categoryId = (int) $_POST['category_id'];
    $imageFile = $_FILES['image'];
    $userId = $_SESSION['user_id'];

    $updateResponse = $articleObj->update($articleId,$userId, $title, $content, $imageFile, $categoryId);

    if ($updateResponse['status'] === 'success') {
        header('Location: article.php?message= article updated successfully');
        exit();
    } else {
        $error = $updateResponse['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Article</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-3xl mx-auto mt-10 bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-4">Update Article</h2>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <!-- Hidden field for the article ID -->
            <input type="hidden" name="id" value="<?= htmlspecialchars($article['id']) ?>">

            <!-- Title Field -->
            <div class="mb-4">
                <label for="title" class="block text-gray-700 font-medium">Title</label>
                <input type="text" id="title" name="title" 
                       value="<?= htmlspecialchars($article['title']) ?>" 
                       class="w-full border rounded-lg px-3 py-2" required>
            </div>

            <!-- Content Field -->
            <div class="mb-4">
                <label for="content" class="block text-gray-700 font-medium">Content</label>
                <textarea id="content" name="content" 
                          class="w-full border rounded-lg px-3 py-2" 
                          rows="6" required><?= htmlspecialchars($article['content']) ?></textarea>
            </div>

            <!-- Category Field -->
            <div class="mb-4">
                <label for="category_id" class="block text-gray-700 font-medium">Category</label>
                <select id="category_id" name="category_id" 
                        class="w-full border rounded-lg px-3 py-2" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" 
                                <?= $category['id'] === $article['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Image Upload Field -->
            <div class="mb-4">
                <label for="image" class="block text-gray-700 font-medium">Image (optional)</label>
                <input type="file" id="image" name="image" class="w-full border rounded-lg px-3 py-2">
                <small class="text-gray-600">Leave empty to keep the current image.</small>
            </div>

            <!-- Current Image Preview -->
            <?php if (!empty($article['image'])): ?>
                <div class="mb-4">
                    <p class="text-gray-700 font-medium">Current Image:</p>
                    <img src="../<?= htmlspecialchars($article['image']) ?>" 
                         alt="Current Image" class="w-48 h-32 object-cover rounded-md">
                </div>
            <?php endif; ?>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Update Article
                </button>
            </div>
        </form>
    </div>
</body>
</html>
