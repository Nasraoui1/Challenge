<?php

namespace App\Controller;

use App\Core\Form;
use App\Core\View;
use App\Models\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once dirname(__DIR__) . '/vendor/autoload.php'; 

class Security {
    public function login(): void {
        $form = new Form("Login");
        $errors = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            // Find user by email
            $user = (new User())->findByEmail($email);

            if ($user && password_verify($password, $user->getPassword())) {
                // Start session
                session_start();
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['user_email'] = $user->getEmail();
                
                // Redirect to protected area or homepage
                header("Location: /protected-area");
                exit();
            } else {
                $errors[] = "Invalid email or password.";
            }
        }

        $view = new View("Security/login");
        $view->assign("form", $form->build());
        $view->assign("errors", $errors); // Pass errors to the view
        $view->render();
    }

    public function register(): void {
        $form = new Form("Register");
        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User();
            $user->setFirstname($_POST["firstname"]);
            $user->setLastname($_POST["lastname"]);
            $user->setEmail($_POST["email"]);
            $user->setPassword(password_hash($_POST["password"], PASSWORD_DEFAULT));

            // Generate a token
            $token = bin2hex(random_bytes(50));
            $user->setToken($token);
            $user->save();

            // Send confirmation email
            $this->sendConfirmationEmail($user->getEmail(), $token);
            echo "Registration successful! Please check your email to confirm your account.";
        }

        $view = new View("Security/register");
        $view->assign("form", $form->build());
        $view->render();
    }

    public function confirmEmail(): void {
        $token = $_GET['token'] ?? '';
        if (empty($token)) {
            echo "Invalid token!";
            return;
        }

        $user = (new User())->findByToken($token);

        if ($user) {
            $user->setIsVerified(true);
            $user->setToken(null);
            $user->save();
            echo "Email verified successfully!";
        } else {
            echo "Invalid token!";
        }
    }

    public function forgotPassword(): void {
        $form = new Form("ForgotPassword");
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $_POST["email"];
            $user = (new User())->findByEmail($email);

            if ($user) {
                // Generate a token
                $token = bin2hex(random_bytes(50));
                $user->setToken($token);
                $user->save();

                // Send reset email
                $this->sendResetEmail($user->getEmail(), $token);
                echo "Password reset email sent!";
            } else {
                echo "No user found with this email address.";
            }
        }

        $view = new View("Security/forgot_password");
        $view->assign("form", $form->build());
        $view->render();
    }

    public function resetPassword(): void {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $token = $_POST['token'];
            $new_password = $_POST['new_password'];

            $user = (new User())->findByToken($token);

            if ($user) {
                $user->setPassword(password_hash($new_password, PASSWORD_DEFAULT));
                $user->setToken(null);
                $user->save();
                echo "Password reset successfully!";
            } else {
                echo "Invalid token!";
            }
        } else {
            $token = $_GET['token'] ?? '';
            if (empty($token)) {
                echo "Invalid token!";
                return;
            }

            $view = new View("Security/reset_password");
            $view->assign("token", $token);
            $view->render();
        }
    }

    public function logout(): void {
        echo "Se déconnecter";
    }

    private function sendConfirmationEmail($email, $token) {
        $this->sendEmail($email, 'Confirm your email', 'Click the link to confirm your email: <a href="http://yourdomain.com/confirmEmail?token=' . $token . '">Confirm Email</a>');
    }

    private function sendResetEmail($email, $token) {
        $this->sendEmail($email, 'Reset your password', 'Click the link to reset your password: <a href="http://yourdomain.com/resetPassword?token=' . $token . '">Reset Password</a>');
    }

    private function sendEmail($to, $subject, $body) {
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.mailtrap.io';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'cedde7689c818e'; // Mailtrap username
            $mail->Password   = 'ff222783924cbf'; // Mailtrap password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('from@example.com', 'Mailer');
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
