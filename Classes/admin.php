<?php
require_once 'User.php';

class Admin extends User {
private $db;

    // Method to add a new tag
    public function addTag($name) {
        $sql = "INSERT INTO tags (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':name', $name);
        return $stmt->execute();
    }

    // Method to delete a tag
    public function deleteTag($tag_id) {
        $sql = "DELETE FROM tags WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $tag_id);
        return $stmt->execute();
    }

    // Method to modify a tag
    public function modifyTag($tag_id, $newName) {
        $sql = "UPDATE tags SET name = :name WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':name', $newName);
        $stmt->bindParam(':id', $tag_id);
        return $stmt->execute();
    }

    // Method to view all tags
    public function viewTags() {
        $sql = "SELECT * FROM tags";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to view all users
    public function viewUsers() {
        $sql = "SELECT * FROM use";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to delete a user
    public function deleteUser($user_id) {
        $sql = "DELETE FROM user WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $user_id);
        return $stmt->execute();
    }

    // Method to view all articles
    public function viewArticles() {
        $sql = "SELECT * FROM article";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to delete any article
    public function deleteAnyArticle($article_id) {
        $sql = "DELETE FROM article WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $article_id);
        return $stmt->execute();
    }

    // Method to view statistics
    public function viewStatistics() {
        $statistics = [];

        // Get total articles
        $sql = "SELECT COUNT(*) AS total_articles FROM article";
        $stmt = $this->db->query($sql);
        $statistics['total_articles'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_articles'];

        // Get total users
        $sql = "SELECT COUNT(*) AS total_users FROM user";
        $stmt = $this->db->query($sql);
        $statistics['total_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];

        // Get total tags
        $sql = "SELECT COUNT(*) AS total_tags FROM tags";
        $stmt = $this->db->query($sql);
        $statistics['total_tags'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_tags'];

        return $statistics;
    }

    // Method to view all comments
    public function viewComments() {
        $sql = "SELECT * FROM comment";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to delete any comment
    public function deleteComment($comment_id) {
        $sql = "DELETE FROM comment WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $comment_id);
        return $stmt->execute();
    }
}
?>
