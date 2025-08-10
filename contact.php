<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$loggedInEmail = $_SESSION['user_email'];
$status = $_GET['status'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - MasterCraft Woodworks</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        #contact-form {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        #contact-form h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        #contact-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        #contact-form input,
        #contact-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        #contact-form input[disabled] {
            background-color: #e9ecef;
            cursor: not-allowed;
        }
        #contact-form button {
            background-color: #0056b3;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        #contact-form button:hover {
            background-color: #004494;
        }
        .status-message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            text-align: center;
        }
        .status-message.success {
            background-color: #d4edda;
            color: #155724;
        }
        .status-message.error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <img src="images/logo.jpg" alt="MasterCraft Woodworks Logo" class="logo">
            <div class="header-content">
                <h1>MasterCraft Woodworks</h1>
            </div>
        </div>
    </header>
    <nav>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="services.html">Services</a></li>
            <li><a href="about.html">About Us</a></li>
            <li><a href="team.html">Team</a></li>
            <li><a href="pricing.html">Pricing</a></li>
            <li><a href="testimonials.html">Testimonials</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="gallery.html">Gallery</a></li>
            <li><a href="order.php">Custom Order</a></li>
            <li><a href="user_orders.php">Pending Orders</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <section id="contact-form">
        <div class="container">
            <h2>Get in Touch</h2>
            <?php if ($status === 'success'): ?>
                <div class="status-message success">Your message has been sent successfully!</div>
            <?php elseif ($status === 'error'): ?>
                <div class="status-message error">There was an error sending your message. Please try again.</div>
            <?php endif; ?>
            <form action="send_email.php" method="POST">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email_display" value="<?php echo htmlspecialchars($loggedInEmail); ?>" disabled>
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($loggedInEmail); ?>">
                </div>
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" required>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                <button type="submit">Send Message</button>
            </form>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2025 MasterCraft Woodworks. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>