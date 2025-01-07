<?php

class Likes {
    private $db;
    public $id;
    public $user_id;
    public $article_id;
    public $statut;
    public $created_at;
    public $updated_at;

    // Constructor to initialize the database connection
    public function __construct($db) {
        $this->db = $db;
    }

    // Method to create a new like
    public function createLike($user_id, $article_id, $statut) {
        $sql = "INSERT INTO likes (user_id, article_id, statut) VALUES (:user_id, :article_id, :statut)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':article_id', $article_id);
        $stmt->bindParam(':statut', $statut);
        return $stmt->execute();
    }

    // Method to load a like by ID
    public function loadLikeById($id) {
        $sql = "SELECT * FROM likes WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $this->id = $result['id'];
            $this->user_id = $result['user_id'];
            $this->article_id = $result['article_id'];
            $this->statut = $result['statut'];
            $this->created_at = $result['created_at'];
            $this->updated_at = $result['updated_at'];
        } else {
            throw new Exception("Like not found.");
        }
    }

    // Method to delete a like
    public function deleteLike($id) {
        $sql = "DELETE FROM likes WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Method to get all likes for a specific article
    public static function getLikesByArticleId($db, $article_id) {
        $sql = "SELECT * FROM likes WHERE article_id = :article_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':article_id', $article_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
