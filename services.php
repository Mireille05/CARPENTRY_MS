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
    <title>Services - MasterCraft Woodworks</title>
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
        .service {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #fff;
        }
        .service h3 {
            margin: 10px 0;
            color: #333;
        }
        .service p {
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

    <section id="services">
        <div class="container">
            <h2>Our Carpentry Services</h2>
            <p>We offer a wide range of carpentry services to meet your needs. Whether you are looking for custom furniture, cabinetry, or home renovations, we have the expertise to deliver exceptional results.</p>
            <div class="service">
                <h3>Cabinetry</h3>
                <p>Our cabinetry services include the design and installation of kitchen cabinets, bathroom vanities, and storage solutions that maximize space and enhance aesthetics.</p>
            </div>
            <div class="service">
                <h3>Home Renovations</h3>
                <p>We provide comprehensive home renovation services, including structural changes, flooring, and more. Our team works closely with you to transform your home into a space you'll love.</p>
            </div>
            <div class="service">
                <h3>Decking</h3>
                <p>We design and build custom decks that extend your living space outdoors. Our decks are built to last, using high-quality materials and expert craftsmanship.</p>
            </div>
            <div class="service">
                <h3>Repair Services</h3>
                <p>We offer repair services for all types of wooden structures and furniture. Our skilled craftsmen can restore your items to their original beauty and functionality.</p>
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