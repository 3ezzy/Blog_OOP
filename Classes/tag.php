<?php
class Tag {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Add a new tag (admin only)
    public function addTag(string $name, int $userId): array {
        if (!$this->isAdmin($userId)) {
            return ['status' => 'error', 'message' => 'Permission denied: Only admins can add tags'];
        }

        try {
            $stmt = $this->db->prepare("INSERT INTO tags (name) VALUES (:name)");
            $stmt->execute(['name' => $name]);

            return ['status' => 'success', 'message' => 'Tag added successfully'];
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') { // Unique constraint violation
                return ['status' => 'error', 'message' => 'Tag already exists'];
            }
            return ['status' => 'error', 'message' => 'Failed to add tag: ' . $e->getMessage()];
        }
    }

    // Delete a tag by ID (admin only)
    public function deleteTag(int $tagId, int $userId): array {
        if (!$this->isAdmin($userId)) {
            return ['status' => 'error', 'message' => 'Permission denied: Only admins can delete tags'];
        }

        try {
            $stmt = $this->db->prepare("DELETE FROM tags WHERE id = :id");
            $stmt->execute(['id' => $tagId]);

            return ['status' => 'success', 'message' => 'Tag deleted successfully'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Failed to delete tag: ' . $e->getMessage()];
        }
    }

    // List all tags
    public function listTags(): array {
        try {
            $stmt = $this->db->query("SELECT * FROM tags");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Failed to fetch tags: ' . $e->getMessage()];
        }
    }
    
      // Assign a tag to an article
    public function assignTagToArticle(int $tagId, int $articleId): array {
        try {
            // // Check if the tag is already assigned to the article
            // $stmt = $this->db->prepare("SELECT id FROM article_tags WHERE tag_id = :tag_id AND article_id = :article_id");
            // $stmt->execute(['tag_id' => $tagId, 'article_id' => $articleId]);
            // if ( $stmt->fetch()) {
            //     echo  $stmt ;
            //     return ['status' => 'error', 'message' => 'Tag already assigned to this article'];
            // }

            // Assign the tag
            $stmt = $this->db->prepare("INSERT INTO article_tags (tag_id, article_id) VALUES (:tag_id, :article_id)");
            $stmt->execute(['tag_id' => $tagId, 'article_id' => $articleId]);
            return ['status' => 'success', 'message' => 'Tag assigned to article successfully'];
        } catch (PDOException $e) {
            if ($e->getCode() === "23000") {
                return ['status' => 'error', 'message' => 'Tag already assigned to this article: '.$e->getMessage()];
            }
            return ['status' => 'error', 'message' => 'Failed to assign tag to article: ' . $e->getMessage()];
        }
    }

    // Check if the user is an admin
    private function isAdmin(int $userId): bool {

        return isset($_SESSION['user_id'], $_SESSION['role']) && $_SESSION['user_id'] === $userId && $_SESSION['role'] === 'admin';
    }
}
