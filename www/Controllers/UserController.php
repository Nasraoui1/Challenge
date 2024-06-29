<?php

namespace App\Controller; // Namespace declaration must be the very first statement

use App\Core\SQL;
use App\Models\Users;

class UserController {
    private $sql;

    public function __construct() {
        $this->sql = new SQL(); // Instantiate SQL class for database operations
    }

    public function registerUser($email, $password, $firstname, $lastname, $birthdate, $address, $phone) {
        // Create a new user object
        $user = new Users();
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT)); // Hash the password
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setBirthdate($birthdate);
        $user->setAddress($address);
        $user->setPhone($phone);
        $user->setToken(uniqid()); // Example: Generate and set a unique token
        $user->setIsVerified(false); // Example: Default to not verified

        // Save user to database
        if ($user->save()) {
            // Optionally, handle response or redirect
            // Example: return success or redirect to login page
            header('Location: /login.php'); // Redirect to login page
            exit();
        } else {
            echo "Registration failed.";
        }
    }

    public function loginUser($email, $password) {
        // Find user by email
        $user = (new Users())->findByEmail($email);

        if ($user && password_verify($password, $user->getPassword())) {
            // Set session variables, redirect, etc.
            $_SESSION['user_id'] = $user->getId(); // Example: Assuming 'user_id' is returned from login method
            header('Location: /dashboard.php'); // Redirect to dashboard or another page
            exit();
        } else {
            // Handle failed login
            echo "Login failed: Incorrect email or password.";
        }
    }
}
