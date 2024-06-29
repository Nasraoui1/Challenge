<?php

namespace App\Controller;

use App\Core\Form;
use App\Core\View;
use App\Models\Users;
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
            $user = (new Users())->findByEmail($email);

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
        $errors = [];
    
        if ($form->isSubmitted() && $form->isValid()) {
            $firstname = $_POST["firstname"] ?? '';
            $lastname = $_POST["lastname"] ?? '';
            $email = $_POST["email"] ?? '';
            $password = $_POST["password"] ?? '';
            $confirm_password = $_POST["confirm_password"] ?? '';
            $birthdate = $_POST["birthdate"] ?? '';
            $address = $_POST["address"] ?? '';
            $phone = $_POST["phone"] ?? '';
    
            // Check if required fields are empty
            if (empty($firstname)) {
                $errors[] = "First name is required.";
            }
            if (empty($lastname)) {
                $errors[] = "Last name is required.";
            }
            if (empty($email)) {
                $errors[] = "Email is required.";
            }
            if (empty($password)) {
                $errors[] = "Password is required.";
            }
            if ($password !== $confirm_password) {
                $errors[] = "Passwords do not match.";
            }
            if (empty($birthdate)) {
                $errors[] = "Date of birth is required.";
            }
            if (empty($address)) {
                $errors[] = "Address is required.";
            }
            if (empty($phone)) {
                $errors[] = "Phone number is required.";
            }
    
            if (empty($errors)) {
                $user = new Users();
                $user->setFirstname($firstname);
                $user->setLastname($lastname);
                $user->setEmail($email);
                $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
                $user->setToken(bin2hex(random_bytes(50)));
                $user->setIsVerified(false);
                $user->setBirthdate($birthdate);
                $user->setAddress($address);
                $user->setPhone($phone);
                $user->save();
    
                // Send confirmation email
                $this->sendConfirmationEmail($user->getEmail(), $user->getToken());
                echo "Registration successful! Please check your email to confirm your account.";
            }
        }
    
        $view = new View("Security/register");
        $view->assign("form", $form->build());
        $view->assign("errors", $errors); // Pass errors to the view
        $view->render();
    }
    
    public function confirmEmail(): void {
        $token = $_GET['token'] ?? '';
        if (empty($token)) {
            echo "Invalid token!";
            return;
        }

        $user = (new Users())->findByToken($token);

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
            $user = (new Users())->findByEmail($email);

            if ($user) {
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

            $user = (new Users())->findByToken($token);

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
        session_start();
        session_unset();
        session_destroy();
        header("Location: /");
        exit();
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
            $mail->Host       = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'cedde7689c818e'; // Mailtrap username
            $mail->Password   = 'ff222783924cbf'; // Mailtrap password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
    
            // Disable SSL verification (temporary)
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ],
            ];
    
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
