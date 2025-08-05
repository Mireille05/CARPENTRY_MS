<?php
// Database configuration
$host = 'localhost';
$user = 'postgres';     // Your PostgreSQL username
$pass = 'kubem';        // Your PostgreSQL password
$dbname = 'postgres';   // Your PostgreSQL database name

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $order_id = $_POST['order_id'];
        $status = $_POST['status'];

        $stmt = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $order_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: admin_orders.php?success=Status updated successfully");
            exit();
        } else {
            echo "Error: Could not update status.";
        }
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
