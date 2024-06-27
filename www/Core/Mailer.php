<?php

require_once '/path/to/vendor/autoload.php'; // Replace with the actual path to autoload.php

use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;

function sendConfirmationEmail($email, $token) {
    $to = $email;
    $subject = 'Confirmation d\'inscription';
    $message = 'Click <a href="http://example.com/views/public/confirm.php?token=' . $token . '">here</a> to confirm your registration';
    
    // Mailtrap SMTP configuration (replace with your actual SMTP details)
    $transport = (new Swift_SmtpTransport('smtp.mailtrap.io', 587))
        ->setUsername('cedde7689c818e')
        ->setPassword('ff222783924cbf');

    $mailer = new Swift_Mailer($transport);

    $message = (new Swift_Message($subject))
        ->setFrom(['from@example.com' => 'Mailer'])
        ->setTo([$to])
        ->setBody($message, 'text/html');

    $result = $mailer->send($message);
    if ($result) {
        echo 'Confirmation email has been sent';
    } else {
        echo 'Failed to send confirmation email';
    }
}

function sendResetEmail($email, $token) {
    $to = $email;
    $subject = 'Password Reset';
    $message = 'Click <a href="http://example.com/views/public/reset_password.php?token=' . $token . '">here</a> to reset your password';

    // Mailtrap SMTP configuration (replace with your actual SMTP details)
    $transport = (new Swift_SmtpTransport('smtp.mailtrap.io', 587))
        ->setUsername('cedde7689c818e')
        ->setPassword('ff222783924cbf');

    $mailer = new Swift_Mailer($transport);

    $message = (new Swift_Message($subject))
        ->setFrom(['from@example.com' => 'Mailer'])
        ->setTo([$to])
        ->setBody($message, 'text/html');

    $result = $mailer->send($message);
    if ($result) {
        echo 'Reset email has been sent';
    } else {
        echo 'Failed to send reset email';
    }
}

// Example usage:
$email = 'recipient@example.com';
$token = 'your_generated_token';

// Uncomment one of the following lines to test sending a confirmation or reset email
 sendConfirmationEmail($email, $token);
 sendResetEmail($email, $token);
