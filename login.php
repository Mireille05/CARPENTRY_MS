<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// PostgreSQL connection
$host = 'localhost';
$dbname = 'postgres';
$username = 'postgres';
$password = 'kubem';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ Database connection failed: " . htmlspecialchars($e->getMessage()));
}

// Sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(trim($data));
}

// Handle login form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = sanitizeInput($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';

    // Validate inputs
    if (empty($email) || empty($password)) {
        $error = "Email and password are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Check user in database
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND is_verified = true");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $error = "Email not found or not verified.";
            } elseif (!password_verify($password, $user['password'])) {
                $error = "Incorrect password.";
            } else {
                // Set session variables
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_id'] = $user['id'];
                header("Location: home.php");
                exit;
            }
        } catch (PDOException $e) {
            $error = "Database error: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MasterCraft Woodworks</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F5F5F5;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
        h2 {
            color: #333333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        label {
            display: block;
            color: #555555;
            margin-bottom: 5px;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #2E7D32;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #D84315;
        }
        .error {
            color: #D32F2F;
            margin-bottom: 15px;
        }
        .links {
            margin-top: 15px;
        }
        .links a {
            color: #2E7D32;
            text-decoration: none;
            margin: 0 10px;
        }
        .links a:hover {
            color: #D84315;
            text-decoration: underline;
        }
        footer {
            margin-top: auto;
            padding: 20px;
            background-color: #333333;
            color: white;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="images/logo.jpg" alt="MasterCraft Woodworks Logo" class="logo">
        <h2>Login</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
       <div class="links">
    <p>Not registered? <a href="register.php">Sign up</a></p>
    <p><a href="forgot_password.php">Forgot Password?</a></p>
    <p><a href="admin_login.php">Admin Login</a></p>
</div>
    </div>
    <footer>
        <div class="container">
            <p id ="date"></p>
        </div>
    </footer>
</body>
<script>
     const kub = document.getElementById("date");
      let date = new Date().getFullYear();
      console.log(date);
      kub.textContent = ` © ${date} MasterCraft Woodworks. All rights reserved.`;
      console.log(kub);
</script>
</html>