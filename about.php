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
    <title>About Us - MasterCraft Woodworks</title>
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
        h2 {
            color: #333;
            margin-bottom: 15px;
        }
        p, ul {
            color: #555;
            line-height: 1.6;
        }
        ul {
            list-style-type: disc;
            padding-left: 20px;
        }
        .btn {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background-color: #218838;
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

    <section id="about">
        <div class="container">
            <h2>Who We Are</h2>
            <p>MasterCraft Woodworks is a premier carpentry service provider, known for our passion for woodcraft and our commitment to quality. With years of experience in the industry, our team of skilled artisans and craftsmen are dedicated to creating beautiful, functional, and durable woodwork that stands the test of time.</p>

            <h2>Our Mission</h2>
            <p>Our mission is to blend traditional craftsmanship with modern design to deliver exceptional carpentry solutions. We believe in the power of custom-made woodwork to transform spaces and enhance lives. Whether it’s a single piece of custom furniture or a complete home renovation, we aim to exceed expectations with every project.</p>

            <h2>Why Choose Us?</h2>
            <ul>
                <li><strong>Experience:</strong> Decades of combined experience in the industry.</li>
                <li><strong>Quality:</strong> We use the finest materials and techniques.</li>
                <li><strong>Customization:</strong> Every project is tailored to your specific needs.</li>
                <li><strong>Customer Satisfaction:</strong> Our clients' satisfaction is our top priority.</li>
            </ul>

            <h2>Our Values</h2>
            <p>At MasterCraft Woodworks, we value integrity, craftsmanship, and customer service. We take pride in our work and strive to build lasting relationships with our clients based on trust and mutual respect.</p>

            <a href="contact.php" class="btn">Get in Touch</a>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2025 MasterCraft Woodworks. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>