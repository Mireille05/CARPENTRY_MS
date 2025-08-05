<?php
// Database configuration for PostgreSQL
$host = 'localhost';
$dbname = 'postgres';  // change if needed
$username = 'postgres'; // your PostgreSQL username
$password = 'kubem';    // your PostgreSQL password

try {
    // Use pgsql driver in DSN
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $subject = htmlspecialchars(trim($_POST["subject"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit;
    }

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
        echo "Message received successfully.";
    } catch (PDOException $e) {
        echo "Error inserting data: " . $e->getMessage();
        exit;
    }
} else {
    echo "Invalid request.";
}
?>
