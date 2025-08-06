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
    <title>Cabinetry - MasterCraft Woodworks</title>
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
        .item {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #fff;
            text-align: center;
        }
        .item img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }
        .item h3 {
            margin: 10px 0;
            color: #333;
        }
        .item p {
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
            <img src="images/logo.jfif" alt="MasterCraft Woodworks Logo" class="logo">
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

    <section id="cabinetry">
        <div class="container">
            <h2>Our Cabinetry Collection</h2>
            <div class="item">
                <h3>Modern Kitchen Cabinet</h3>
                <p>Price: $800</p>
                <button class="btn" onclick="addToCart('Modern Kitchen Cabinet', 800, 'Standard Size', 'Sleek and contemporary kitchen storage')">Add to Order</button>
            </div>
            <div class="item">
                <h3>Classic Wooden Cabinet</h3>
                <p>Price: $750</p>
                <button class="btn" onclick="addToCart('Classic Wooden Cabinet', 750, 'Standard Size', 'Timeless wooden design')">Add to Order</button>
            </div>
            <div class="item">
                <h3>Elegant Bathroom Cabinet</h3>
                <p>Price: $600</p>
                <button class="btn" onclick="addToCart('Elegant Bathroom Cabinet', 600, 'Compact Size', 'Stylish bathroom storage')">Add to Order</button>
            </div>
            <div class="item">
                <h3>Custom Wardrobe Cabinet</h3>
                <p>Price: $950</p>
                <button class="btn" onclick="addToCart('Custom Wardrobe Cabinet', 950, 'Large Size', 'Customizable wardrobe solution')">Add to Order</button>
            </div>
            <div class="item">
                <h3>Vintage Storage Cabinet</h3>
                <p>Price: $700</p>
                <button class="btn" onclick="addToCart('Vintage Storage Cabinet', 700, 'Medium Size', 'Rustic storage cabinet')">Add to Order</button>
            </div>
            <div class="item">
                <h3>Minimalist Office Cabinet</h3>
                <p>Price: $850</p>
                <button class="btn" onclick="addToCart('Minimalist Office Cabinet', 850, 'Standard Size', 'Sleek office storage')">Add to Order</button>
            </div>
        </div>
    </section>

    <section id="cart">
        <div class="container">
            <a href="order.php" class="btn">View Cart</a>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2025 MasterCraft Woodworks. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function addToCart(name, price, size, description) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            const existingItem = cart.find(item => item.name === name);
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({ name, price, quantity: 1, size, description });
            }
            localStorage.setItem('cart', JSON.stringify(cart));
            alert(`${name} added to cart!`);
        }
    </script>
</body>
</html>