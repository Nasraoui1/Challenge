<?php
require_once '../../src/config/Database.php';
require_once '../../src/repository/UserRepository.php';

use Repository\UserRepository;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];

    $userRepository = new UserRepository();
    $result = $userRepository->resetPassword($token, $new_password);

    if ($result) {
        echo "Password reset successfully!";
    } else {
        echo "Invalid token!";
    }
} else {
    $token = $_GET['token'];
?>
    <form action="reset_password.php" method="POST">
        <input type="hidden" name="token" value="<?= htmlentities($token) ?>">
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" required>
        <input type="submit" value="Reset Password">
    </form>
<?php
}
?>
