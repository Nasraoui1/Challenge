<?php

namespace App\Models;

use App\Core\SQL;
use PDO;
use PDOException;

class Users extends SQL
{
    private ?int $id = null;
    private string $username;
    private string $lastname;
    private string $firstname;
    private string $birthdate;
    private string $email;
    private string $password;
    private ?string $token = null;
    private bool $is_verified = false;

    public function __construct()
    {
        parent::__construct();
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getBirthdate(): string
    {
        return $this->birthdate;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getIsVerified(): bool
    {
        return $this->is_verified;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function setBirthdate(string $birthdate): void
    {
        $this->birthdate = $birthdate;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    public function setIsVerified(bool $is_verified): void
    {
        $this->is_verified = $is_verified;
    }

    // Save method and other methods remain the same as previously mentioned

    // Save method for inserting or updating user in database
    public function save(): bool
    {
        try {
            if (empty($this->id)) {
                // Insert new user
                $sql = "INSERT INTO chall_users (username, lastname, firstname, birthdate, email, password, token, is_verified) 
                        VALUES (:username, :lastname, :firstname, :birthdate, :email, :password, :token, :is_verified)";
            } else {
                // Update existing user
                $sql = "UPDATE chall_users SET username = :username, lastname = :lastname, firstname = :firstname, 
                        birthdate = :birthdate, email = :email, password = :password, token = :token, is_verified = :is_verified 
                        WHERE id = :id";
            }

            $stmt = $this->pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':username', $this->username, PDO::PARAM_STR);
            $stmt->bindParam(':lastname', $this->lastname, PDO::PARAM_STR);
            $stmt->bindParam(':firstname', $this->firstname, PDO::PARAM_STR);
            $stmt->bindParam(':birthdate', $this->birthdate, PDO::PARAM_STR);
            $stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $this->password, PDO::PARAM_STR);
            $stmt->bindParam(':token', $this->token, PDO::PARAM_STR);
            $stmt->bindParam(':is_verified', $this->is_verified, PDO::PARAM_BOOL);

            if (!empty($this->id)) {
                $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            }

            $stmt->execute();

            if (empty($this->id)) {
                $this->id = $this->pdo->lastInsertId();
            }

            return true;
        } catch (PDOException $e) {
            die('SQL Error: ' . $e->getMessage());
        }

        return false;
    }

    // Find user by email
    public function findByEmail(string $email): ?self
    {
        $stmt = $this->pdo->prepare("SELECT * FROM chall_users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return $this->mapDataToUser($data);
        }
        return null;
    }

    // Find user by token
    public function findByToken(string $token): ?self
    {
        $stmt = $this->pdo->prepare("SELECT * FROM chall_users WHERE token = :token");
        $stmt->execute(['token' => $token]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return $this->mapDataToUser($data);
        }
        return null;
    }

    // Map database data to user object
    private function mapDataToUser(array $data): self
    {
        $user = new self();
        $user->setId($data['id']);
        $user->setUsername($data['username']);
        $user->setLastname($data['lastname']);
        $user->setFirstname($data['firstname']);
        $user->setBirthdate($data['birthdate']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setToken($data['token']);
        $user->setIsVerified($data['is_verified']);
        return $user;
    }
}
