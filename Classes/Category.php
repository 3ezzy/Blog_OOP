<?php

class Category {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Add a new category
    public function addCategory(string $name): array {
        try {
            // Prepare the SQL statement
            $stmt = $this->db->prepare("INSERT INTO categories (name) VALUES (:name)");
            $stmt->execute(['name' => $name]);
    
            return ['status' => 'success', 'message' => 'Category added successfully'];
        } catch (PDOException $e) {
            // Check for duplicate entry error (SQLSTATE 23000)
            if ($e->getCode() === '23000') {
                return ['status' => 'error', 'message' => 'Category already exists'];
            }
            return ['status' => 'error', 'message' => 'Failed to add category: ' . $e->getMessage()];
        }
    }
    

    // Delete a category
    public function deleteCategory(int $categoryId): array {
        if (!$this->isAdmin()) {
            return ['status' => 'error', 'message' => 'Permission denied. Only admins can delete categories.'];
        }

        try {
            $stmt = $this->db->prepare("DELETE FROM categories WHERE id = :id");
            $stmt->execute(['id' => $categoryId]);
            return ['status' => 'success', 'message' => 'Category deleted successfully'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Failed to delete category: ' . $e->getMessage()];
        }
    }

    // List all categories
    public function listCategories(): array {
        try {
            $stmt = $this->db->query("SELECT * FROM categories");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Failed to retrieve categories: ' . $e->getMessage()];
        }
    }

    // Check if the current user is an admin
    private function isAdmin(): bool {
        return isset($_SESSION["role"]) && $_SESSION["role"] === "admin";
    }
}

