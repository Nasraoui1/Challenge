<?php

function sendConfirmationEmail($email, $token) {
    $to = $email;
    $subject = 'Confirmation d\'inscription';
    $message = 'Click <a href="http://example.com/views/public/confirm.php?token=' . $token . '">here</a> to confirm your registration';
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <from@example.com>' . "\r\n";

    // Mailtrap SMTP configuration
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
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <from@example.com>' . "\r\n";

    // Mailtrap SMTP configuration
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
