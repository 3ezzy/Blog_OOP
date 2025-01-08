<?php
require_once 'database.php';
class Article
{
    public $id;
    public $title;
    public $content;
    public $image;
    private $user_id;

    public function __construct($id = "", $title = "", $content = "", $image = "", $user_id = "")
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->image = $image;
        $this->user_id = $user_id;
    }



    // Load an article by its id
    public function loadArticleById($id)
    {

        $db = new Database();
        $getcon = $db->getConnection();
        $sql = "SELECT * FROM article WHERE id = :id"; // Changed to 'article'
        $stmt = $getcon->query($sql);
        $result = $stmt->fetch_assoc();

        if ($result) {
            $this->id = $result['id'];
            $this->title = $result['title'];
            $this->content = $result['content'];
            $this->image = $result['image'];
            $this->user_id = $result['user_id'];
        } else {
            throw new Exception("Article not found.");
        }
    }
    public function getArticles()
    {
        $db = new Database();
        $getcon = $db->getConnection();

        $sql = "SELECT * FROM article";
        $stmt = $getcon->query($sql);
        return $stmt->fetch_assoc();
    }

    // Create a new article
    public function createArticle($title, $content, $image, $user_id)
    {

        $db = new Database();
        $getcon = $db->getConnection();
        $sql = "INSERT INTO article (title, content, image, user_id) VALUES (:title, :content, :image, :user_id)"; // Changed to 'article'
        $stmt = $getcon->query($sql);
        $title->bindParam(':title', $title);
        $content->bindParam(':content', $content);
        $image->bindParam(':image', $image);
        $user_id->bindParam(':user_id', $user_id);
        return $stmt->fetch_assoc();
    }

    // Update an existing article
    public function updateArticle($title, $content, $image)
    {
        $db = new Database();
        $getcon = $db->getConnection();
        $sql = "UPDATE article SET title = :title, content = :content, image = :image WHERE id = :id";
        $stmt = $getcon->query($sql);
        $title->bindParam(':title', $title);
        $content->bindParam(':content', $content);
        $image->bindParam(':image', $image);
        $id = (int)$this->id;
        return $stmt->fetch_assoc();
    }

    // Delete an article
    public function deleteArticle()
    {
        $db = new Database();
        $getcon = $db->getConnection();
        $sql = "DELETE FROM article WHERE id = :id"; 
        $stmt = $getcon->query($sql);
        $id = (int)$this->id;
        return $stmt->fetch_assoc();
    }

    // Add a tag to an article
    public function addTag($tag_id)
    {
        $db = new Database();
        $getcon = $db->getConnection();

        $sql = "INSERT INTO article_tags (article_id, tag_id) VALUES (:article_id, :tag_id)";
        $stmt = $getcon->query($sql);
        $article_id = (int)$this->id;
        $tag_id = (int)$tag_id;
        return $stmt->fetch_assoc();
    }

    // Remove a tag from an article
    public function removeTag($tag_id)
    {
        $db = new Database();
        $getcon = $db->getConnection();
        $sql = "DELETE FROM article_tags WHERE article_id = :article_id AND tag_id = :tag_id";
        $stmt = $getcon->prepare($sql);
        $article_id = (int)$this->id;
        $tag_id = (int)$tag_id;
        return $stmt->execute();
    }

    // Get all articles
    public static function getAllArticles($db)
    {
        $sql = "
            SELECT article.*, users.username, GROUP_CONCAT(tags.name SEPARATOR ', ') AS tags
            FROM article
            JOIN users ON article.user_id = users.id
            LEFT JOIN article_tags ON article.id = article_tags.article_id
            LEFT JOIN tags ON article_tags.tag_id = tags.id
            GROUP BY article.id
        ";
        $stmt = $db->query($sql);
        return $stmt->fetch_assoc();
    }
}
