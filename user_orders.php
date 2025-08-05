<?php
session_start();

$host = 'localhost';
$dbname = 'postgres';  // change if needed
$user = 'postgres';
$pass = 'kubem';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Insert new order
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $location = $_POST['location'];
        $items = $_POST['items'];
        $total = $_POST['total'];

        $stmt = $pdo->prepare("
            INSERT INTO orders (name, email, phone, location, items, total)
            VALUES (:name, :email, :phone, :location, :items, :total)
        ");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':location' => $location,
            ':items' => $items,
            ':total' => $total,
        ]);

        echo "<script>alert('Order placed successfully!'); window.location.href = 'myorder.php';</script>";
        exit();
    }

    // Fetch all orders to display
    $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
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
    </style>
</head>
<body>
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
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $row): 
                    $order_items = json_decode($row['items'], true);
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
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>
</body>
</html>
