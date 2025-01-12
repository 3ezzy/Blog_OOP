<?php
class Article {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Upload image
     public function uploadImage(array $file): string {
        $fileName = basename($file["name"]);
        $targetFilePath = "uploads/images/" . uniqid() . "_" . $fileName;

        // Move the uploaded file
        if (!move_uploaded_file($file["tmp_name"], "../".$targetFilePath)) {
            throw new Exception("Failed to upload image.");
        }

        return $targetFilePath;
    }

    // Create a new article
    public function create(string $title, string $content, array $imageFile, int $userId, int $categoryId): array {
        try {
            $imagePath = $this->uploadImage($imageFile);

            $stmt = $this->db->prepare("INSERT INTO articles (title, content, image, user_id, category_id, created_at) VALUES (:title, :content, :image, :user_id, :category_id, NOW())");
            $stmt->execute([
                'title' => $title,
                'content' => $content,
                'image' => $imagePath,
                'user_id' => $userId,
                'category_id' => $categoryId,
            ]);

            return ['status' => 'success', 'message' => 'Article created successfully'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Failed to create article: ' . $e->getMessage()];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    // Read an article by its ID
    public function read(int $id): array {
        $stmt = $this->db->prepare("SELECT * FROM articles WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($article) {
            return ['status' => 'success', 'data' => $article];
        } else {
            return ['status' => 'error', 'message' => 'Article not found'];
        }
    }

    // Update an article by its ID
    public function update(int $id, int $userId, string $title, string $content, array|null $imageFile, int $categoryId): array {
        try {
            // Verify ownership of the article
            $checkStmt = $this->db->prepare("SELECT user_id FROM articles WHERE id = :id");
            $checkStmt->execute(['id' => $id]);
            $article = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$article) {
                return ['status' => 'error', 'message' => 'Article not found'];
            }
    
            if ($article['user_id'] !== $userId) {
                return ['status' => 'error', 'message' => 'Permission denied. You can only update your own articles.'];
            }
    
            $imagePath = null;
    
            // Handle image upload if a new file is provided
            if ($imageFile && $imageFile['tmp_name']) {
                $imagePath = $this->uploadImage($imageFile);
            }
    
            // Build the update query
            $query = "UPDATE articles SET title = :title, content = :content, category_id = :category_id, updated_at = NOW()";
            $params = [
                'title' => $title,
                'content' => $content,
                'category_id' => $categoryId,
                'id' => $id,
            ];
    
            if ($imagePath) {
                $query .= ", image = :image";
                $params['image'] = $imagePath;
            }
    
            $query .= " WHERE id = :id";
    
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
    
            return ['status' => 'success', 'message' => 'Article updated successfully'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Failed to update article: ' . $e->getMessage()];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
    

    // Delete an article by its ID
    public function delete(int $id, int $userId): array {
        try {
            // Check if the article belongs to the logged-in user
            $checkStmt = $this->db->prepare("SELECT user_id FROM articles WHERE id = :id");
            $checkStmt->execute(['id' => $id]);
            $article = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$article) {
                return ['status' => 'error', 'message' => 'Article not found'];
            }
    
            if ($article['user_id'] !== $userId) {
                return ['status' => 'error', 'message' => 'Permission denied. You can only delete your own articles.'];
            }
    
            // Delete the article
            $stmt = $this->db->prepare("DELETE FROM articles WHERE id = :id");
            $stmt->execute(['id' => $id]);
    
            return ['status' => 'success', 'message' => 'Article deleted successfully'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Failed to delete article: ' . $e->getMessage()];
        }
    }
    

    // List all articles
    public function listAll(): array {
        $stmt = $this->db->query("SELECT * FROM articles ORDER BY created_at DESC");
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['status' => 'success', 'data' => $articles];
    }
    public function getUserArticles(int $userId): array {
        try {
            $stmt = $this->db->prepare("SELECT * FROM articles WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
    
            $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return [
                'status' => 'success',
                'data' => $articles
            ];
        } catch (PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to fetch user articles: ' . $e->getMessage()
            ];
        }
    }
    // Get article by ID
public function getById(int $id): array {
    try {
        $stmt = $this->db->prepare("SELECT * FROM articles WHERE id = :id");
        $stmt->execute(['id' => $id]);
        
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($article) {
            return ['status' => 'success', 'data' => $article];
        } else {
            return ['status' => 'error', 'message' => 'Article not found'];
        }
    } catch (PDOException $e) {
        return ['status' => 'error', 'message' => 'Failed to retrieve article: ' . $e->getMessage()];
    }
}
}
