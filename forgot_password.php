<?php
session_start();

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
    die(" Database connection failed: " . htmlspecialchars($e->getMessage()));
}

// Sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(trim($data));
}

// Generate OTP (6 digits)
function generateOTP($length = 6) {
    return sprintf("%06d", rand(0, 999999));
}

$error = null;
$success = null;
$show_otp_form = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['action']) && $_POST['action'] === 'request_otp') {
        $email = sanitizeInput($_POST["email"] ?? '');

        // Validate email
        if (empty($email)) {
            $error = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        } else {
            try {
                // Check if email exists and is verified
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND is_verified = true");
                $stmt->execute([':email' => $email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$user) {
                    $error = "Email not found or not verified.";
                } else {
                    // Generate and store OTP
                    $otp = generateOTP();
                    $expiry = date('Y-m-d H:i:s', time() + 300); // OTP expires in 5 minutes

                    // Store OTP in password_resets table
                    $stmt = $pdo->prepare("INSERT INTO password_resets (email, otp, expiry) VALUES (:email, :otp, :expiry)");
                    $stmt->execute([
                        ':email' => $email,
                        ':otp' => $otp,
                        ':expiry' => $expiry
                    ]);

                    // Store email and OTP in session for verification
                    $_SESSION['reset_email'] = $email;
                    $_SESSION['reset_otp'] = $otp;
                    $_SESSION['otp_expiry'] = time() + 300;

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

                        $mail->setFrom('your-email@gmail.com', 'MasterCraft Woodworks');
                        $mail->addAddress($email);
                        $mail->Subject = 'Your Password Reset OTP';
                        $mail->Body = "Hello,\n\nYour OTP code for password reset is: $otp\n\nUse it within 5 minutes to reset your password.";

                        $mail->send();
                        $show_otp_form = true;
                    } catch (Exception $e) {
                        $error = "Mailer error: {$mail->ErrorInfo}";
                    }
                }
            } catch (PDOException $e) {
                $error = "Database error: " . htmlspecialchars($e->getMessage());
            }
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'verify_otp') {
        $user_otp = sanitizeInput($_POST['otp'] ?? '');

        // Check OTP and expiry
        if (!isset($_SESSION['reset_otp']) || time() > $_SESSION['otp_expiry']) {
            $error = 'OTP expired or invalid.';
        } elseif ($user_otp !== $_SESSION['reset_otp']) {
            $error = 'Invalid OTP.';
        } else {
            // OTP is valid, redirect to reset password page
            $_SESSION['verified_reset_email'] = $_SESSION['reset_email'];
            header("Location: reset_password.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - MasterCraft Woodworks</title>
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
        .success {
            color: #2E7D32;
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
            color: white;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="images/logo.jpg" alt="MasterCraft Woodworks Logo" class="logo">
        <?php if ($show_otp_form): ?>
            <h2>Verify OTP</h2>
            <p>We sent a code to <?php echo htmlspecialchars($_SESSION['reset_email']); ?>. Enter it below.</p>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form method="POST">
                <input type="hidden" name="action" value="verify_otp">
                <div class="form-group">
                    <label for="otp">OTP Code:</label>
                    <input type="text" id="otp" name="otp" required>
                </div>
                <button type="submit">Verify OTP</button>
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
            </script>
        <?php else: ?>
            <h2>Forgot Password</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <p class="success"><?php echo htmlspecialchars($success); ?></p>
            <?php endif; ?>
            <form method="POST">
                <input type="hidden" name="action" value="request_otp">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                <button type="submit">Send OTP</button>
            </form>
            <div class="links">
                <p><a href="login.php">Back to Login</a></p>
                <p><a href="register.php">Sign up</a></p>
            </div>
        <?php endif; ?>
    </div>
    <footer>
        <div class="container">
            <p>&copy; 2025 MasterCraft Woodworks. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>