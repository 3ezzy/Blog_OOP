<?php
session_start();
include "../db/database.php";
include "../classes/Article.php";
include "../classes/Category.php";

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/register&login.php');
    exit();
}
include "../pages/dashboard.php";


$user = $_SESSION['user_id'];

// Initialize database, Article, and Category classes
$db = (new Database())->connect();
$articleObj = new Article($db);
$categoryObj = new Category($db);

// Fetch all articles
$articlesResponse = $articleObj->getUserArticles($user);
$articles = $articlesResponse['status'] === 'success' ? $articlesResponse['data'] : [];

// Fetch all categories
$categoriesResponse = $categoryObj->listCategories();
$categories = $categoriesResponse;
?>

<body class="bg-gray-100">
<main class="ml-40">

    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Manage Articles</h1>
            <button id="add-article-btn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add Article
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($articles as $article): ?>
                <div class="bg-white shadow-md rounded-lg p-4">
                    <img src="../<?= htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['title']) ?>" class="w-full h-48 object-cover rounded-lg mb-4">
                    <h2 class="text-lg font-semibold"><?= htmlspecialchars($article['title']) ?></h2>
                    <p class="text-gray-700"><?= htmlspecialchars(substr($article['content'], 0, 100)) ?>...</p>
                    <div class="flex justify-between mt-4">
                        <a href="../dashboard/update_article.php?id=<?= htmlspecialchars($article['id']) ?>" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            Update
                        </a>
                        <form action="../dashboard/delete_article.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this article?');">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($article['id']) ?>">
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Add Article Modal -->
    <div id="add-article-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white w-96 p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-bold mb-4">Add New Article</h2>
            <form action="../dashboard/add_article.php" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="title" class="block text-gray-700">Title</label>
                    <input type="text" id="title" name="title" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label for="content" class="block text-gray-700">Content</label>
                    <textarea id="content" name="content" class="w-full border rounded-lg px-3 py-2" rows="4" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="category_id" class="block text-gray-700">Category</label>
                    <select id="category_id" name="category_id" class="w-full border rounded-lg px-3 py-2" required>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['id']) ?>"><?= htmlspecialchars($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="image" class="block text-gray-700">Image</label>
                    <input type="file" id="image" name="image" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div class="flex justify-end">
                    <button type="button" id="cancel-btn" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Cancel</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add</button>
                </div>
            </form>
        </div>
    </div>
</main>
<script>
    // Modal functionality
    const addArticleBtn = document.getElementById('add-article-btn');
    const addArticleModal = document.getElementById('add-article-modal');
    const cancelBtn = document.getElementById('cancel-btn');

    addArticleBtn.addEventListener('click', () => {
        addArticleModal.classList.remove('hidden');
    });

    cancelBtn.addEventListener('click', () => {
        addArticleModal.classList.add('hidden');
    });
</script>
</body>
</html>