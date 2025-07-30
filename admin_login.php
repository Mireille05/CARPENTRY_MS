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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch admin user from the database
    $sql = "SELECT * FROM admin WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        // Password is correct, create a session
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $admin['username'];

        // Redirect to admin dashboard
        header('Location: admin_dashboard.php');
        exit();
    } else {
        echo "Invalid username or password.";
    }
}



$conn->close();
?>
