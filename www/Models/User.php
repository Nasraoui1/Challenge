<?php

namespace Model;

use App\Core\SQL;
use PDO;

class User extends SQL {
    private ?int $id = null;
    private string $lastname;
    private string $firstname;
    private string $birthdate;
    private string $mail;
    private string $password;
    private ?string $token = null;
    private bool $is_verified = false;

    public function __construct($id = null, $lastname = "", $firstname = "", $birthdate = "", $mail = "", $password = "") {
        $this->id = $id;
        $this->lastname = $lastname;
        $this->firstname = $firstname;
        $this->birthdate = $birthdate;
        $this->mail = $mail;
        $this->password = $password;
    }

    // Getters and Setters
    public function getId(): ?int {
        return $this->id;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function getLastname(): string {
        return $this->lastname;
    }

    public function setLastname(string $lastname): void {
        $this->lastname = $lastname;
    }

    public function getFirstname(): string {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): void {
        $this->firstname = $firstname;
    }

    public function getBirthdate(): string {
        return $this->birthdate;
    }

    public function setBirthdate(string $birthdate): void {
        $this->birthdate = $birthdate;
    }

    public function getMail(): string {
        return $this->mail;
    }

    public function setMail(string $mail): void {
        $this->mail = $mail;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function getToken(): ?string {
        return $this->token;
    }

    public function setToken(?string $token): void {
        $this->token = $token;
    }

    public function getIsVerified(): bool {
        return $this->is_verified;
    }

    public function setIsVerified(bool $is_verified): void {
        $this->is_verified = $is_verified;
    }

    // Methods to find users by email/token and save user details
    public function findByEmail(string $email): ?self {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return $this->mapDataToUser($data);
        }
        return null;
    }

    public function findByToken(string $token): ?self {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE token = :token");
        $stmt->execute(['token' => $token]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return $this->mapDataToUser($data);
        }
        return null;
    }

    public function save(): void {
        if ($this->id === null) {
            $stmt = $this->pdo->prepare("INSERT INTO users (lastname, firstname, birthdate, mail, password, token, is_verified) VALUES (:lastname, :firstname, :birthdate, :mail, :password, :token, :is_verified)");
            $stmt->execute([
                'lastname' => $this->lastname,
                'firstname' => $this->firstname,
                'birthdate' => $this->birthdate,
                'mail' => $this->mail,
                'password' => $this->password,
                'token' => $this->token,
                'is_verified' => $this->is_verified
            ]);
            $this->id = $this->pdo->lastInsertId();
        } else {
            $stmt = $this->pdo->prepare("UPDATE users SET lastname = :lastname, firstname = :firstname, birthdate = :birthdate, mail = :mail, password = :password, token = :token, is_verified = :is_verified WHERE id = :id");
            $stmt->execute([
                'id' => $this->id,
                'lastname' => $this->lastname,
                'firstname' => $this->firstname,
                'birthdate' => $this->birthdate,
                'mail' => $this->mail,
                'password' => $this->password,
                'token' => $this->token,
                'is_verified' => $this->is_verified
            ]);
        }
    }

    private function mapDataToUser(array $data): self {
        $user = new self();
        $user->setId($data['id']);
        $user->setLastname($data['lastname']);
        $user->setFirstname($data['firstname']);
        $user->setBirthdate($data['birthdate']);
        $user->setMail($data['mail']);
        $user->setPassword($data['password']);
        $user->setToken($data['token']);
        $user->setIsVerified($data['is_verified']);
        return $user;
    }
}
