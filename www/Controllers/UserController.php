<?php
// File: UserController.php

namespace App\Controller;

use App\Core\SQL;
use App\Models\User;

class UserController {
    private $sql;

    public function __construct() {
        $this->sql = new SQL(); // Instantiate SQL class for database operations
    }

    public function registerUser($username, $email, $password) {
        // Create a new user object
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT)); // Hash the password
        $user->setToken(uniqid()); // Example: Generate and set a unique token
        $user->setIsVerified(false); // Example: Default to not verified

        // Save user to database
        $this->sql->save($user);

        // Optionally, handle response or redirect
        // Example: return success or redirect to login page
    }

    public function loginUser($email, $password) {
        // Authenticate user
        $result = $this->sql->login($email, $password);

        // Handle login result
        if ($result['success']) {
            // Set session variables, redirect, etc.
            $_SESSION['user_id'] = $result['user_id']; // Example: Assuming 'user_id' is returned from login method
            header('Location: /dashboard.php'); // Redirect to dashboard or another page
            exit();
        } else {
            // Handle failed login
            echo "Login failed: " . $result['message'];
        }
    }
}
