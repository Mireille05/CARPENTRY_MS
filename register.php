<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$host = 'localhost';
$dbname = 'postgres';
$user = 'postgres';
$pass = 'kubem';

// Generate OTP (6 digits)
function generateOTP($length = 6) {
    return rand(pow(10, $length-1), pow(10, $length)-1);
}

// Sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

$error = null;
$show_otp_form = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'register') {
        $email = sanitizeInput($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validate inputs
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email format';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters';
        } else {
            // Generate OTP and store in session
            $otp = generateOTP();
            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;
            $_SESSION['otp_expiry'] = time() + 300; // OTP expires in 5 minutes

            // Send OTP email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'kelysemireille@gmail.com'; // Replace with your Gmail
                $mail->Password = 'axjf rtsm jmzk kudj'; // Replace with your Gmail App Password
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
                $mail->addAddress($email);
                $mail->Subject = 'Your OTP Code';
                $mail->Body = "Hello,\n\nYour OTP code is: $otp\n\nUse it within 5 minutes to verify your email.";

                $mail->send();
                $show_otp_form = true;
            } catch (Exception $e) {
                $error = "Mailer error: {$mail->ErrorInfo}";
            }
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'verify') {
        $user_otp = sanitizeInput($_POST['otp'] ?? '');

        // Check OTP and expiry
        if (!isset($_SESSION['otp']) || time() > $_SESSION['otp_expiry']) {
            $error = 'OTP expired or invalid.';
        } elseif ($user_otp != $_SESSION['otp']) {
            $error = 'Invalid OTP.';
        } else {
            // OTP is valid, create account
            $email = $_SESSION['email'];
            $password = $_SESSION['password'];
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            try {
                $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Check if email already exists
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
                $stmt->execute([':email' => $email]);
                if ($stmt->fetchColumn() > 0) {
                    $error = 'Email already registered.';
                } else {
                    // Insert user
                    $stmt = $pdo->prepare("INSERT INTO users (email, password, is_verified) VALUES (:email, :password, true)");
                    $stmt->execute([
                        ':email' => $email,
                        ':password' => $hashed_password
                    ]);

                    // Clear session
                    unset($_SESSION['otp'], $_SESSION['email'], $_SESSION['password'], $_SESSION['otp_expiry']);

                    // Redirect to login with success message
                    $_SESSION['success'] = 'Account created successfully! Please log in.';
                    header("Location: login.php");
                    exit;
                }
            } catch (PDOException $e) {
                $error = "Database error: " . htmlspecialchars($e->getMessage());
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - MasterCraft Woodworks</title>
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
        #timer {
            color: #555555;
            margin-top: 10px;
        }
        footer {
            margin-top: auto;
            padding: 20px;
            background-color: #333333;
            color: black;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="images/logo.jpg" alt="MasterCraft Woodworks Logo" class="logo">
        <?php if ($show_otp_form): ?>
            <h2>Verify Your Email</h2>
            <p>We sent a code to <?php echo htmlspecialchars($_SESSION['email']); ?>. Enter it below.</p>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form method="POST">
                <input type="hidden" name="action" value="verify">
                <div class="form-group">
                    <label for="otp">OTP Code:</label>
                    <input type="text" id="otp" name="otp" required>
                </div>
                <button type="submit">Verify</button>
            </form>
            <p id="timer"></p>
            <div class="links">
                <p><a href="login.php">Back to Login</a></p>
            </div>
            <script>
                let timeLeft = <?php echo $_SESSION['otp_expiry'] - time(); ?>;
                const timer = setInterval(() => {
                    if (timeLeft <= 0) {
                        clearInterval(timer);
                        document.getElementById("timer").innerText = "OTP expired";
                    } else {
                        let minutes = Math.floor(timeLeft / 60);
                        let seconds = timeLeft % 60;
                        document.getElementById("timer").innerText = `${minutes}:${seconds < 10 ? "0" : ""}${seconds}`;
                        timeLeft--;
                    }
                }, 1000);


                const kub = document.getElementById("date");
                let date = new Date().getFullYear();
                console.log(date);
                 kub.textContent = ` © ${date} MasterCraft Woodworks. All rights reserved.`;
                 console.log(kub);
            </script>
        <?php else: ?>
            <h2>Register</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form method="POST">
                <input type="hidden" name="action" value="register">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Send OTP</button>
            </form>
            <div class="links">
                <p>Already have an account? <a href="login.php">Log in</a></p>
                <p><a href="admin_login.php">Admin Login</a></p>
            </div>
        <?php endif; ?>
    </div>
    <footer>
        <div class="container">
            <p id ="date"></p>
        </div>
    </footer>
</body>
</html>