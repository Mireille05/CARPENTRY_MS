<?php
// Database configuration for PostgreSQL
$host = 'localhost';
$dbname = 'postgres';    // Update with your DB name
$user = 'postgres';      // Your PostgreSQL username
$pass = 'kubem';         // Your PostgreSQL password

try {
    // Create PDO connection for PostgreSQL
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch data from the database
    $sql = "SELECT * FROM orders ORDER BY created_at DESC";
    $stmt = $pdo->query($sql);

    // Begin HTML output with CSS
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Order Summary</title>
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
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
            }
            th, td {
                padding: 15px;
                text-align: left;
                border-bottom: 1px solid #ddd;
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
            .order-items {
                max-width: 600px;
                margin: 0 auto;
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
        <h1>Order Summary</h1>';

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($rows) > 0) {
        echo "<table>";
        echo "<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Location</th><th>Order Items</th><th>Order Total</th><th>Status</th><th>Created At</th></tr></thead>";
        echo "<tbody>";

        foreach ($rows as $row) {
            // Decode order items JSON
            $order_items = json_decode($row['items'], true);
            $order_items_display = '';
            if (is_array($order_items)) {
                foreach ($order_items as $item) {
                    $order_items_display .= "<div>";
                    $order_items_display .= "Name: " . htmlspecialchars($item['name']) . "<br>";
                    $order_items_display .= "Price: $" . htmlspecialchars($item['price']) . "<br>";
                    $order_items_display .= "Quantity: " . htmlspecialchars($item['quantity']) . "<br>";
                    $order_items_display .= "Size: " . htmlspecialchars($item['size']) . "<br>";
                    $order_items_display .= "Description: " . htmlspecialchars($item['description']) . "<br>";
                    $order_items_display .= "Total: $" . htmlspecialchars($item['total']) . "<br>";
                    $order_items_display .= "</div>";
                }
            }

            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
            echo "<td>" . htmlspecialchars($row['location']) . "</td>";
            echo "<td class='order-items'>" . $order_items_display . "</td>";
            echo "<td>$" . htmlspecialchars($row['total']) . "</td>";
            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
            echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>No records found.</p>";
    }

    echo '</body></html>';

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
