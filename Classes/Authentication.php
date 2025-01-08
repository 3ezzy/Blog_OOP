<?php

    require_once '../Classes/database.php';

class Authentication
{

    public function login($email, $password)
    {

        $db = new Database();
        $connection = $db->getConnection();


        $sql = "SELECT * FROM user WHERE email = '$email'";
        $user = $connection->query($sql);

        return $user->fetch_assoc();
    }


    public function logout()
    {
        unset($_SESSION['user']);
        header('Location: login.php');
        exit();
    }

    public function verifierSession()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: login.php');
            exit();
        }
    }

    public function signup($full_name, $username, $email, $password, $role)
    {

        $db = new Database();
        $connection = $db->getConnection();

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO user (full_name, username, email, password, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("sssss", $full_name, $username, $email, $hashedPassword, $role);

        return $stmt->execute();
    }
}
