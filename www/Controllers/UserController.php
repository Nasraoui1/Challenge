<?php

namespace Controller;

use Repository\UserRepository;
use Model\User;

require_once '../config/Database.php';
require_once '../Repository/UserRepository.php';

class UserController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function insertUser(string $lastname, string $firstname, string $password, string $mail, int $age): int {
        return $this->userRepository->insertUser($lastname, $firstname, $password, $mail, $age);
    }


    public function getUserByEmail(string $mail): ?User {
        return $this->userRepository->findUserByEmail($mail);
    }

    public function deleteUserById(int $id): bool {
        return $this->userRepository->deleteUserById($id);
    }

    public function modifyUser(User $user, int $id): bool {
        return $this->userRepository->modifyUserById($user, $id);
    }

    public function affichage(): array {
        return $this->userRepository->AfficherUser();
    }
}
