<?php

class Comment
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Create a comment
    public function createComment(int $articleId, int $userId, string $content): array
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO comments (article_id, user_id, content, created_at) VALUES (:article_id, :user_id, :content, NOW())");
            $stmt->execute([
                'article_id' => $articleId,
                'user_id' => $userId,
                'content' => $content,
            ]);
            return ['status' => 'success', 'message' => 'Comment added successfully'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Failed to add comment: ' . $e->getMessage()];
        }
    }

    // Read all comments for an article
    public function getCommentsByArticle(int $articleId): array
    {
        try {
            $stmt = $this->db->prepare("SELECT c.id, c.content, c.created_at, u.username 
                                         FROM comments c 
                                         JOIN users u ON c.user_id = u.id 
                                         WHERE c.article_id = :article_id 
                                         ORDER BY c.created_at DESC");
            $stmt->execute(['article_id' => $articleId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Failed to fetch comments: ' . $e->getMessage()];
        }
    }

    // Delete a comment
    public function deleteComment(int $commentId, int $userId): array
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM comments WHERE id = :comment_id AND user_id = :user_id");
            $stmt->execute([
                'comment_id' => $commentId,
                'user_id' => $userId,
            ]);
            if ($stmt->rowCount() > 0) {
                return ['status' => 'success', 'message' => 'Comment deleted successfully'];
            }
            return ['status' => 'error', 'message' => 'No comment found or permission denied'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Failed to delete comment: ' . $e->getMessage()];
        }
    }
}
