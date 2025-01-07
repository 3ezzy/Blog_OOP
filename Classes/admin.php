<?php
require_once 'user.php';

class Admin extends User {
    private $db;
    

    // Method to create a new user
    public function createUser($username, $password, $email) {
        $sql = "INSERT INTO user (username, password, email) VALUES (:username, :password, :email)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', password_hash($password, PASSWORD_DEFAULT)); // Hashing password
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }

    // Method to delete a user
    public function deleteUser($user_id) {
        $sql = "DELETE FROM user WHERE id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }

    // Method to update user details
    public function updateUser($user_id, $username, $email) {
        $sql = "UPDATE user SET username = :username, email = :email WHERE id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }

    // Method to manage articles (e.g., create, update, delete)
    public function createArticle($title, $content, $user_id) {
        $sql = "INSERT INTO article (title, content, user_id) VALUES (:title, :content, :user_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }

    // Method to delete an article
    public function deleteArticle($article_id) {
        $sql = "DELETE FROM article WHERE id = :article_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':article_id', $article_id);
        return $stmt->execute();
    }

    // Method to update an article
    public function updateArticle($article_id, $title, $content) {
        $sql = "UPDATE article SET title = :title, content = :content WHERE id = :article_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':article_id', $article_id);
        return $stmt->execute();
    }

    // Method to view all users
    public function getAllUsers() {
        $sql = "SELECT * FROM user";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to view all articles
    public function getAllArticles() {
        $sql = "SELECT * FROM article";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to view all comments
    public function getAllComments() {
        $sql = "SELECT * FROM comment";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to delete a comment
    public function deleteComment($comment_id) {
        $sql = "DELETE FROM comment WHERE id = :comment_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':comment_id', $comment_id);
        return $stmt->execute();
    }
}
?>
