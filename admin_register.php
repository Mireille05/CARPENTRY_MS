<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// DB config
$host = 'localhost';
$dbname = 'postgres';
$user = 'postgres';
$pass = 'kubem';

// Generate OTP
function generateOTP($length = 6) {
    return rand(pow(10, $length - 1), pow(10, $length) - 1);
}

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

$error = null;
$show_otp_form = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'register') {
        $email = sanitizeInput($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email format';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters';
        } else {
            $otp = generateOTP();
            $_SESSION['admin_otp'] = $otp;
            $_SESSION['admin_email'] = $email;
            $_SESSION['admin_password'] = $password;
            $_SESSION['admin_otp_expiry'] = time() + 300;

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'alainfabricehirwa@gmail.com';
                $mail->Password = 'vocb ahzx srlh xqey';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('your-email@gmail.com', 'MasterCraft Admin');
                $mail->addAddress($email);
                $mail->Subject = 'Admin OTP Code';
                $mail->Body = "Hello Admin,\n\nYour OTP code is: $otp\n\nUse it within 5 minutes.";

                $mail->send();
                $show_otp_form = true;
            } catch (Exception $e) {
                $error = "Mailer error: {$mail->ErrorInfo}";
            }
        }
    } elseif ($_POST['action'] === 'verify') {
        $user_otp = sanitizeInput($_POST['otp'] ?? '');

        if (!isset($_SESSION['admin_otp']) || time() > $_SESSION['admin_otp_expiry']) {
            $error = 'OTP expired or invalid.';
        } elseif ($user_otp != $_SESSION['admin_otp']) {
            $error = 'Invalid OTP.';
        } else {
            $email = $_SESSION['admin_email'];
            $password = $_SESSION['admin_password'];
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            try {
                $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $pdo->prepare("SELECT COUNT(*) FROM admin WHERE username = :email");
                $stmt->execute([':email' => $email]);
                if ($stmt->fetchColumn() > 0) {
                    $error = 'Admin already registered.';
                } else {
                    $stmt = $pdo->prepare("INSERT INTO admin (username, password) VALUES (:email, :password)");
                    $stmt->execute([
                        ':email' => $email,
                        ':password' => $hashed_password
                    ]);

                    unset($_SESSION['admin_otp'], $_SESSION['admin_email'], $_SESSION['admin_password'], $_SESSION['admin_otp_expiry']);

                    $_SESSION['success'] = 'Admin registered successfully!';
                    header("Location: admin_login.php");
                    exit;
                }
            } catch (PDOException $e) {
                $error = "Database error: " . $e->getMessage();
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px #aaa;
            width: 350px;
            text-align: center;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 12px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Admin Registration</h2>
    <?php if (isset($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($show_otp_form): ?>
        <form method="POST">
            <input type="hidden" name="action" value="verify">
            <label for="otp">Enter OTP sent to <?= htmlspecialchars($_SESSION['admin_email']) ?>:</label>
            <input type="text" name="otp" required>
            <button type="submit">Verify OTP</button>
            <p id="timer"></p>
        </form>
        <script>
            let timeLeft = <?= $_SESSION['admin_otp_expiry'] - time(); ?>;
            const timerDisplay = document.getElementById("timer");
            const timer = setInterval(() => {
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    timerDisplay.innerText = "OTP expired";
                } else {
                    let m = Math.floor(timeLeft / 60);
                    let s = timeLeft % 60;
                    timerDisplay.innerText = `Expires in ${m}:${s < 10 ? "0" + s : s}`;
                    timeLeft--;
                }
            }, 1000);
        </script>
    <?php else: ?>
        <form method="POST">
            <input type="hidden" name="action" value="register">
            <input type="email" name="email" placeholder="Admin Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Send OTP</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
