<?php
session_start();
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database configuration
$host = 'localhost';
$dbname = 'postgres';
$username = 'postgres';
$password = 'kubem';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    header("Location: contact.php?status=error");
    exit;
}

// Sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitizeInput($_POST["name"] ?? '');
    $email = sanitizeInput($_POST["email"] ?? '');
    $subject = sanitizeInput($_POST["subject"] ?? '');
    $message = sanitizeInput($_POST["message"] ?? '');

    // Verify session email matches POST email
    if (!isset($_SESSION['user_email']) || $_SESSION['user_email'] !== $email) {
        header("Location: contact.php?status=error");
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: contact.php?status=error");
        exit;
    }

    // Validate other inputs
    if (empty($name) || empty($subject) || empty($message)) {
        header("Location: contact.php?status=error");
        exit;
    }

    // Insert into database
    $sql = "INSERT INTO contact_form (name, email, subject, message) 
            VALUES (:name, :email, :subject, :message)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':subject' => $subject,
            ':message' => $message
        ]);

        // Send email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'kelysemireille@gmail.com';
            $mail->Password = 'axjf rtsm jmzk kudj';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ];

            $mail->setFrom('kelysemireille@gmail.com', 'MasterCraft Woodworks');
            $mail->addAddress('kelysemireille@gmail.com');
            $mail->addReplyTo($email, $name);
            $mail->Subject = "New Contact Form Submission: $subject";
            $mail->Body = "Name: $name\nEmail: $email\nSubject: $subject\nMessage: $message";

            $mail->send();
            header("Location: contact.php?status=success");
            exit;
        } catch (Exception $e) {
            error_log("Mailer Error: {$mail->ErrorInfo}");
            header("Location: contact.php?status=error");
            exit;
        }
    } catch (PDOException $e) {
        error_log("DB Error: " . $e->getMessage());
        header("Location: contact.php?status=error");
        exit;
    }
} else {
    header("Location: contact.php?status=error");
    exit;
}
?>