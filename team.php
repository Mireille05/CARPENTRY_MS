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
    <title>Team - MasterCraft Woodworks</title>
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
        .team-member {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #fff;
            text-align: center;
        }
        .team-member h3 {
            margin: 10px 0;
            color: #333;
        }
        .team-member p {
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

    <section id="team">
        <div class="container">
            <h2>Our Team</h2>
            <div class="team-member">
                <h3>Hirwa Alain </h3>
                <p>Lead Carpenter</p>
                <p>With over 6 years of experience, Fabrice specializes in custom cabinetry and woodworking.</p>
            </div>
            <div class="team-member">
                <h3>ISHIMWE Aurore</h3>
                <p>Project Manager</p>
                <p>Aurore ensures every project is completed on time and to the highest standards of quality.</p>
            </div>
            <div class="team-member">
                <h3>UMUGANWA Lyina</h3>
                <p>Woodworking Specialist</p>
                <p>Lyina has a keen eye for detail and excels in creating intricate wooden designs.</p>
            </div>
            <div class="team-member">
                <h3>HIMBAZA Ediston</h3>
                <p>Interior Designer</p>
                <p>Ediston brings a creative touch to every project, ensuring each piece fits perfectly within its space.</p>
            </div>
            <div class="team-member">
                <h3>Gael BAREMA</h3>
                <p>Master Carpenter</p>
                <p>Gael has a deep understanding of carpentry techniques and materials, leading our most complex projects.</p>
            </div>
            <div class="team-member">
                <h3>Berret AMATA</h3>
                <p>Apprentice Carpenter</p>
                <p>Berret is passionate about learning and quickly becoming an integral part of our team.</p>
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