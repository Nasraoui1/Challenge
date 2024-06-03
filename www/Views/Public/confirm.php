<?php
require_once '../../src/config/Database.php';
require_once '../../src/repository/UserRepository.php';

use Repository\UserRepository;

$token = $_GET['token'];
$userRepository = new UserRepository();

$result = $userRepository->confirmEmail($token);

if ($result) {
    echo "Email verified successfully!";
} else {
    echo "Invalid token!";
}
?>
