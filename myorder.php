<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = 'localhost';
    $dbname = 'postgres';   // Or your actual DB name
    $user = 'postgres';
    $pass = 'kubem';

    try {
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Retrieve form inputs
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $location = $_POST['location'];
        $items = $_POST['items'];
        $total = $_POST['total'];

        $stmt = $pdo->prepare("
            INSERT INTO orders (name, email, phone, location, items, total)
            VALUES (:name, :email, :phone, :location, :items, :total)
        ");

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':items', $items);
        $stmt->bindParam(':total', $total);

        if ($stmt->execute()) {
            echo "<script>alert('Order placed successfully!'); window.location.href = 'index.php';</script>";
            exit();
        } else {
            echo "Error: Could not submit order.";
        }

    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    }
}
?>
