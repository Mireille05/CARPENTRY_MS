=<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$host = 'localhost';
$dbname = 'postgres';
$user = 'postgres';
$pass = 'kubem';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch orders for the logged-in user
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders - MasterCraft Woodworks</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }
        th {
            background-color: #28a745;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .order-items div {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 4px;
            background-color: #f9f9f9;
        }
        .status {
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
        }
        .status-pending {
            background-color: #ffc107;
            color: #333;
        }
        .status-processing {
            background-color: #17a2b8;
            color: #fff;
        }
        .status-completed {
            background-color: #28a745;
            color: #fff;
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

    <section id="orders">
        <div class="container">
            <h1>Your Orders</h1>
            <?php if (count($orders) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Location</th>
                            <th>Order Items</th>
                            <th>Order Total</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $row): 
                            $order_items = json_decode($row['items'], true);
                            $status = htmlspecialchars($row['status'] ?? 'Pending');
                            $status_class = strtolower($status) === 'pending' ? 'status-pending' :
                                           (strtolower($status) === 'processing' ? 'status-processing' : 'status-completed');
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['phone']) ?></td>
                                <td><?= htmlspecialchars($row['location']) ?></td>
                                <td class="order-items">
                                    <?php
                                    if (is_array($order_items)) {
                                        foreach ($order_items as $item) {
                                            echo '<div>';
                                            echo 'Name: ' . htmlspecialchars($item['name']) . '<br>';
                                            echo 'Price: $' . htmlspecialchars(number_format($item['price'], 2)) . '<br>';
                                            echo 'Quantity: ' . htmlspecialchars($item['quantity']) . '<br>';
                                            echo 'Size: ' . htmlspecialchars($item['size']) . '<br>';
                                            echo 'Description: ' . htmlspecialchars($item['description']) . '<br>';
                                            echo 'Total: $' . htmlspecialchars(number_format($item['total'], 2)) . '<br>';
                                            echo '</div>';
                                        }
                                    } else {
                                        echo 'No items found';
                                    }
                                    ?>
                                </td>
                                <td>$<?= htmlspecialchars(number_format($row['total'], 2)) ?></td>
                                <td><span class="status <?php echo $status_class; ?>"><?= $status ?></span></td>
                                <td><?= htmlspecialchars($row['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No orders found.</p>
            <?php endif; ?>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2025 MasterCraft Woodworks. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>