<?php

class LikeDislike {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Add a like for an article by a user
    public function addLike(int $userId, int $articleId): array {
        try {
            $this->removeDislike($userId, $articleId); // Ensure the user doesn't have a dislike first

            // Check if the user already liked the article
            $stmt = $this->db->prepare("SELECT id FROM likes WHERE user_id = :user_id AND article_id = :article_id");
            $stmt->execute(['user_id' => $userId, 'article_id' => $articleId]);

            if ($stmt->fetch()) {
                return ['status' => 'error', 'message' => 'You have already liked this article'];
            }
            
            // Add like
            $stmt = $this->db->prepare("INSERT INTO likes (user_id, article_id) VALUES (:user_id, :article_id)");
            $stmt->execute(['user_id' => $userId, 'article_id' => $articleId]);
            return ['status' => 'success', 'message' => 'Like added successfully'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Failed to add like: ' . $e->getMessage()];
        }
    }

    // Add a dislike for an article by a user
    public function addDislike(int $userId, int $articleId): array {
        try {
            $this->removeLike($userId, $articleId); // Ensure the user doesn't have a like first

            // Check if the user already disliked the article
            $stmt = $this->db->prepare("SELECT id FROM dislikes WHERE user_id = :user_id AND article_id = :article_id");
            $stmt->execute(['user_id' => $userId, 'article_id' => $articleId]);

            if ($stmt->fetch()) {
                return ['status' => 'error', 'message' => 'You have already disliked this article'];
            }

            // Add dislike
            $stmt = $this->db->prepare("INSERT INTO dislikes (user_id, article_id) VALUES (:user_id, :article_id)");
            $stmt->execute(['user_id' => $userId, 'article_id' => $articleId]);

            return ['status' => 'success', 'message' => 'Dislike added successfully'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Failed to add dislike: ' . $e->getMessage()];
        }
    }

    // Remove a like for an article by a user
    public function removeLike(int $userId, int $articleId): array {
        try {
            $stmt = $this->db->prepare("DELETE FROM likes WHERE user_id = :user_id AND article_id = :article_id");
            $stmt->execute(['user_id' => $userId, 'article_id' => $articleId]);

            return ['status' => 'success', 'message' => 'Like removed successfully'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Failed to remove like: ' . $e->getMessage()];
        }
    }

    // Remove a dislike for an article by a user
    public function removeDislike(int $userId, int $articleId): array {
        try {
            $stmt = $this->db->prepare("DELETE FROM dislikes WHERE user_id = :user_id AND article_id = :article_id");
            $stmt->execute(['user_id' => $userId, 'article_id' => $articleId]);

            return ['status' => 'success', 'message' => 'Dislike removed successfully'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Failed to remove dislike: ' . $e->getMessage()];
        }
    }

    // Get the total number of likes for an article
    public function getLikes(int $articleId): int {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) AS like_count FROM likes WHERE article_id = :article_id");
            $stmt->execute(['article_id' => $articleId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ? (int)$result['like_count'] : 0;
        } catch (PDOException $e) {
            throw new Exception('Failed to retrieve likes: ' . $e->getMessage());
        }
    }

    // Get the total number of dislikes for an article
    public function getDislikes(int $articleId): int {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) AS dislike_count FROM dislikes WHERE article_id = :article_id");
            $stmt->execute(['article_id' => $articleId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ? (int)$result['dislike_count'] : 0;
        } catch (PDOException $e) {
            throw new Exception('Failed to retrieve dislikes: ' . $e->getMessage());
        }
    }
}
