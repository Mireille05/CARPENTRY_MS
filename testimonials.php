<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testimonials - MasterCraft Woodworks</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .testimonial {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #fff;
        }
        .testimonial h3 {
            margin: 10px 0;
            color: #333;
        }
        .testimonial p {
            color: #555;
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
            <li><a href="services.php">Services</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="team.php">Team</a></li>
            <li><a href="pricing.php">Pricing</a></li>
            <li><a href="testimonials.php">Testimonials</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="order.php">Custom Order</a></li>
            <li><a href="user_orders.php">Pending Orders</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <section id="testimonials">
        <div class="container">
            <h2>Client Testimonials</h2>
            <div class="testimonial">
                <h3>Alice KEZA</h3>
                <p>"The craftsmanship is outstanding. The team exceeded my expectations in every way."</p>
            </div>
            <div class="testimonial">
                <h3>Bob Smith</h3>
                <p>"Professional, reliable, and highly skilled. I couldn't be happier with the results."</p>
            </div>
            <div class="testimonial">
                <h3>Hirwa Alain Fabrice</h3>
                <p>"Exceptional service and attention to detail. I highly recommend their services."</p>
            </div>
            <div class="testimonial">
                <h3>David Brown</h3>
                <p>"They transformed my space beautifully. The team is friendly and professional."</p>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2025 MasterCraft Woodworks. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>