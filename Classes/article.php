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
        $sql = "SELECT * FROM article WHERE id = :id";
        $stmt = $getcon->prepare($sql);
        $stmt->bind_param(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

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

    public function getArticlesByUserId($user_id) {
        $db = new Database();
        $getcon = $db->getConnection();
    
        $sql = "SELECT * FROM article WHERE user_id = ?";
        $stmt = $getcon->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getArticles()
    {
        $db = new Database();
        $getcon = $db->getConnection();

        $sql = "SELECT * FROM article";
        $stmt = $getcon->query($sql);
        return $stmt->fetch_all(MYSQLI_ASSOC); // changed from fetch_assoc to fetch_all
    }

    // Create a new article
    public function createArticle($title, $content, $image, $user_id)
    {
        $db = new Database();
        $getcon = $db->getConnection();
        $sql = "INSERT INTO article (title, content, image, user_id) VALUES (?, ?, ?, ?)";
        $stmt = $getcon->prepare($sql);
        $stmt->bind_param('sssi', $title, $content, $image, $user_id);
        $stmt->execute();
        return $stmt->insert_id; // return the inserted ID
    }

    // Update an existing article
    public function updateArticle($title, $content, $image)
    {
        $db = new Database();
        $getcon = $db->getConnection();
        $sql = "UPDATE article SET title = ?, content = ?, image = ? WHERE id = ?";
        $stmt = $getcon->prepare($sql);
        $stmt->bind_param('sssi', $title, $content, $image, $this->id);
        $stmt->execute();
        return $stmt->affected_rows; // return the number of affected rows
    }

    // Delete an article
    public function deleteArticle()
    {
        $db = new Database();
        $getcon = $db->getConnection();
        $sql = "DELETE FROM article WHERE id = ?";
        $stmt = $getcon->prepare($sql);
        $stmt->bind_param('i', $this->id);
        $stmt->execute();
        return $stmt->affected_rows; // return the number of affected rows
    }

    // Add a tag to an article
    public function addTag($tag_id)
    {
        $db = new Database();
        $getcon = $db->getConnection();

        $sql = "INSERT INTO article_tags (article_id, tag_id) VALUES (?, ?)";
        $stmt = $getcon->prepare($sql);
        $stmt->bind_param('ii', $this->id, $tag_id);
        $stmt->execute();
        return $stmt->insert_id; // return the inserted ID
    }

    // Remove a tag from an article
    public function removeTag($tag_id)
    {
        $db = new Database();
        $getcon = $db->getConnection();
        $sql = "DELETE FROM article_tags WHERE article_id = ? AND tag_id = ?";
        $stmt = $getcon->prepare($sql);
        $stmt->bind_param('ii', $this->id, $tag_id);
        $stmt->execute();
        return $stmt->affected_rows; // return the number of affected rows
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
        return $stmt->fetch_all(MYSQLI_ASSOC); // return all articles
    }
}
