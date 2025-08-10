<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$loggedInEmail = $_SESSION['user_email'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Order - MasterCraft Woodworks</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        #page {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            display: inline-block;
            text-decoration: none;
        }
        .btn-primary:hover {
            background-color: #218838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-control[readonly] {
            background-color: #e9ecef;
            cursor: not-allowed;
        }
        button[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin-top: 20px;
            text-align: center;
            font-size: 16px;
        }
        button[type="submit"]:hover {
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
    <div id="page">
        <h2>Order Summary</h2>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Size</th>
                    <th>Description</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="order-items">
                <!-- Order items will be injected here by JavaScript -->
            </tbody>
        </table>
        <p>Total: $<span id="order-total-display">0.00</span></p>
        <form action="myorder.php" method="POST" onsubmit="return updateOrderForm()">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($loggedInEmail); ?>" required readonly>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="tel" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="location">Your Location:</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>
            <input type="hidden" id="order-items-hidden" name="items">
            <input type="hidden" id="order-total-hidden" name="total">
            <button type="submit" class="btn-primary">Place Order</button>
        </form>
    </div>
    
    <footer>
        <div class="container">
            <p id ="date"></p>
        </div>
    </footer>

    <script>
function loadOrder() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    if (cart.length === 0) {
        console.log('Cart is empty');
        return;
    }

    let orderItemsTable = document.getElementById('order-items');
    orderItemsTable.innerHTML = '';
    let total = 0;

    cart.forEach((item, index) => {
        let itemTotal = item.price * item.quantity;
        total += itemTotal;

        let row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.name}</td>
            <td>$${item.price.toFixed(2)}</td>
            <td>
                <button onclick="changeQuantity(${index}, -1)">➖</button>
                ${item.quantity}
                <button onclick="changeQuantity(${index}, 1)">➕</button>
            </td>
            <td>${item.size || 'N/A'}</td>
            <td>${item.description || 'N/A'}</td>
            <td>$${itemTotal.toFixed(2)}</td>
            <td><button onclick="deleteItem(${index})" style="color: red;">🗑</button></td>
        `;
        orderItemsTable.appendChild(row);
    });

    document.getElementById('order-total-display').textContent = total.toFixed(2);
}

function changeQuantity(index, delta) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart[index].quantity += delta;

    if (cart[index].quantity < 1) {
        if (!confirm("Quantity is 0. Do you want to remove this item?")) {
            cart[index].quantity = 1;
        } else {
            cart.splice(index, 1);
        }
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    loadOrder();
}

function deleteItem(index) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    if (confirm("Are you sure you want to remove this item?")) {
        cart.splice(index, 1);
        localStorage.setItem('cart', JSON.stringify(cart));
        loadOrder();
    }
}

function updateOrderForm() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let items = [];
    let total = 0;

    cart.forEach(item => {
        let itemTotal = item.price * item.quantity;
        items.push({
            name: item.name,
            price: item.price,
            quantity: item.quantity,
            size: item.size || 'N/A',
            description: item.description || 'N/A',
            total: itemTotal
        });
        total += itemTotal;
    });

    document.getElementById('order-items-hidden').value = JSON.stringify(items);
    document.getElementById('order-total-hidden').value = total.toFixed(2);

    return true;
}

document.addEventListener('DOMContentLoaded', loadOrder);



 const kub = document.getElementById("date");
      let date = new Date().getFullYear();
      console.log(date);
      kub.textContent = ` © ${date} MasterCraft Woodworks. All rights reserved.`;
      console.log(kub);
    </script>
</body>
</html>