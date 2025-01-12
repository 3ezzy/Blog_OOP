<?php

class Validator {
    private array $errors = [];

    public function validateUsername(string $username): void {
        if (empty($username)) {
            $this->errors['username'] = "Username is required.";
        } elseif (strlen($username) < 3 || strlen($username) > 20) {
            $this->errors['username'] = "Username must be between 3 and 20 characters.";
        }
    }

    public function validatePassword(string $password): void {
        if (empty($password)) {
            $this->errors['password'] = "Password is required.";
        } elseif (strlen($password) < 8) {
            $this->errors['password'] = "Password must be at least 8 characters long.";
        } elseif (!preg_match('/[A-Z]/', $password)) {
            $this->errors['password'] = "Password must include at least one uppercase letter.";
        } elseif (!preg_match('/[0-9]/', $password)) {
            $this->errors['password'] = "Password must include at least one number.";
        }
    }

    public function validateEmail(string $email): void {
        if (empty($email)) {
            $this->errors['email'] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = "Invalid email format.";
        }
    }

    public function getErrors(): array {
        return $this->errors;
    }

    public function isValid(): bool {
        return empty($this->errors);
    }
}
