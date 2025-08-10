<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = 'localhost';
    $dbname = 'postgres';
    $user = 'postgres';
    $pass = 'kubem';

    try {
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Retrieve and sanitize form inputs
        $name = htmlspecialchars(trim($_POST['name'] ?? ''));
        $email = htmlspecialchars(trim($_POST['email'] ?? ''));
        $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
        $location = htmlspecialchars(trim($_POST['location'] ?? ''));
        $items = $_POST['items'] ?? '';
        $total = $_POST['total'] ?? '';

        // Validate email matches session
        if ($email !== $_SESSION['user_email']) {
            echo "<script>alert('Invalid email. Please try again.'); window.location.href = 'order.php';</script>";
            exit();
        }

        // Validate inputs
        if (empty($name) || empty($email) || empty($phone) || empty($location) || empty($items) || empty($total)) {
            echo "<script>alert('All fields are required.'); window.location.href = 'order.php';</script>";
            exit();
        }

        $stmt = $pdo->prepare("
            INSERT INTO orders (name, email, phone, location, items, total, user_id)
            VALUES (:name, :email, :phone, :location, :items, :total, :user_id)
        ");

        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':location' => $location,
            ':items' => $items,
            ':total' => $total,
            ':user_id' => $_SESSION['user_id']
        ]);

        // Clear cart after successful order
        echo "<script>
            localStorage.removeItem('cart');
            alert('Order placed successfully!');
            window.location.href = 'home.php';
        </script>";
        exit();

    } catch (PDOException $e) {
        echo "<script>alert('Database Error: " . htmlspecialchars($e->getMessage()) . "'); window.location.href = 'order.php';</script>";
        exit();
    }
} else {
    header("Location: order.php");
    exit();
}
?>