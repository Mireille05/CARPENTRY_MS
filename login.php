<?php
session_start();

// Database configuration
$host = 'localhost';
$user = 'postgres';
$pass = 'kubem';
$dbname = 'postgres';

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Correct PDO prepared statement
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // $user['password'] is the hashed password from DB
        if (password_verify($password, $user['password'])) {
            // Password is correct
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $email;
            header("Location: index.php");
            exit();
        } else {
            header("Location: login.php?error=Incorrect password");
            exit();
        }
    } else {
        header("Location: register.php?error=Account does not exist. Please register.");
        exit();
    }
}

// No need to close PDO connections; PHP does it automatically
?>
