<?php
class Article {
    private $db;
    public $id;
    public $title;
    public $content;
    public $image;
    private $user_id;

    

    // Load an article by its id
    public function loadArticleById($id) {
        $sql = "SELECT * FROM article WHERE id = :id"; // Changed to 'article'
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
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

    // Create a new article
    public function createArticle($title, $content, $image, $user_id) {
        $sql = "INSERT INTO article (title, content, image, user_id) VALUES (:title, :content, :image, :user_id)"; // Changed to 'article'
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }

    // Update an existing article
    public function updateArticle($title, $content, $image) {
        $sql = "UPDATE article SET title = :title, content = :content, image = :image WHERE id = :id"; // Changed to 'article'
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    // Delete an article
    public function deleteArticle() {
        $sql = "DELETE FROM article WHERE id = :id"; // Changed to 'article'
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    // Add a tag to an article
    public function addTag($tag_id) {
        $sql = "INSERT INTO article_tags (article_id, tag_id) VALUES (:article_id, :tag_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':article_id', $this->id);
        $stmt->bindParam(':tag_id', $tag_id);
        return $stmt->execute();
    }

    // Remove a tag from an article
    public function removeTag($tag_id) {
        $sql = "DELETE FROM article_tags WHERE article_id = :article_id AND tag_id = :tag_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':article_id', $this->id);
        $stmt->bindParam(':tag_id', $tag_id);
        return $stmt->execute();
    }

    // Get all articles
    public static function getAllArticles($db) {
        $sql = "
            SELECT article.*, users.username, GROUP_CONCAT(tags.name SEPARATOR ', ') AS tags
            FROM article
            JOIN users ON article.user_id = users.id
            LEFT JOIN article_tags ON article.id = article_tags.article_id
            LEFT JOIN tags ON article_tags.tag_id = tags.id
            GROUP BY article.id
        ";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
