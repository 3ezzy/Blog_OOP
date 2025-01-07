<?php

class Comment {
    private $db;
    public $id;
    public $content;
    public $article_id;
    public $user_id;
    public $created_at;
    public $updated_at;

    // Method to create a new comment
    public function createComment($content, $article_id, $user_id) {
        $sql = "INSERT INTO comment (content, article_id, user_id) VALUES (:content, :article_id, :user_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':article_id', $article_id);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }

    // Method to load a comment by ID
    public function loadCommentById($id) {
        $sql = "SELECT * FROM comment WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $this->id = $result['id'];
            $this->content = $result['content'];
            $this->article_id = $result['article_id'];
            $this->user_id = $result['user_id'];
            $this->created_at = $result['created_at'];
            $this->updated_at = $result['updated_at'];
        } else {
            throw new Exception("Comment not found.");
        }
    }

    // Method to delete a comment
    public function deleteComment($id) {
        $sql = "DELETE FROM comment WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Method to get all comments for a specific article
    public static function getCommentsByArticleId($db, $article_id) {
        $sql = "SELECT * FROM comment WHERE article_id = :article_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':article_id', $article_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
