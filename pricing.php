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
    <title>Pricing - MasterCraft Woodworks</title>
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
        .plan {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #fff;
            text-align: center;
        }
        .plan h3 {
            margin: 10px 0;
            color: #333;
        }
        .plan p {
            color: #555;
        }
        .btn {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
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

    <section id="pricing">
        <div class="container">
            <h2>Our Pricing Plans</h2>
            <p>Choose a pricing plan that fits your needs and budget. We offer flexible options to cater to different project sizes and complexities.</p>
            <div class="plan">
                <h3>Basic Plan</h3>
                <p>$30</p>
                <ul>
                    <li>Consultation and Planning</li>
                    <li>Basic Material Selection</li>
                    <li>Standard Workmanship</li>
                    <li>Delivery within 30 days</li>
                </ul>
                <a href="contact.php" class="btn">Select Plan</a>
            </div>
            <div class="plan">
                <h3>Standard Plan</h3>
                <p>$60</p>
                <ul>
                    <li>Consultation and Planning</li>
                    <li>Premium Material Selection</li>
                    <li>High-Quality Workmanship</li>
                    <li>Delivery within 20 days</li>
                </ul>
                <a href="contact.php" class="btn">Select Plan</a>
            </div>
            <div class="plan">
                <h3>Premium Plan</h3>
                <p>$100</p>
                <ul>
                    <li>Consultation and Planning</li>
                    <li>Exclusive Material Selection</li>
                    <li>Top-Tier Workmanship</li>
                    <li>Delivery within 10 days</li>
                </ul>
                <a href="contact.php" class="btn">Select Plan</a>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p id = "date"></p>
        </div>
    </footer>
    <script>
      const kub = document.getElementById("date");
      let date = new Date().getFullYear();
      console.log(date);
      kub.textContent = ` © ${date} MasterCraft Woodworks. All rights reserved.`;
      console.log(kub);
    </script>
     
</body>
</html>