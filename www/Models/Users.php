<?php

namespace App\Models;

use App\Core\SQL;
use PDO;
use PDOException;

class Users extends SQL
{
    private ?int $id = null;
    private string $lastname;
    private string $firstname;
    private string $birthdate;
    private string $email;
    private string $password;
    private ?string $token = null;
    private bool $is_verified = false;
    private ?string $address = null;
    private ?string $phone = null;

    public function __construct()
    {
        parent::__construct();
    }

    // Getters 
    public function getId(): ?int
    {
        return $this->id;
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
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

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
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
        $user->setLastname($data['lastname']);
        $user->setFirstname($data['firstname']);
        $user->setBirthdate($data['birthdate']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setToken($data['token']);
        $user->setIsVerified($data['is_verified']);
        $user->setAddress($data['address']);
        $user->setPhone($data['phone']);
        return $user;
    }
}
