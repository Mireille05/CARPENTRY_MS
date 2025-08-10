<?php
// Database configuration
$host = 'localhost';
$dbname = 'postgres';
$user = 'postgres';
$pass = 'kubem';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Insert an admin user (hashed password for security)
    $username = 'kelysemireille@gmail.com';
    $password = password_hash('doitforothers', PASSWORD_BCRYPT);

    $sql = "INSERT INTO admin (username, password) VALUES (:username, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':username' => $username, ':password' => $password]);

    echo "Admin user created successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>