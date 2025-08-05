<?php
// Database configuration for PostgreSQL
$host = 'localhost';
$dbname = 'postgres';    // Update to your DB name
$user = 'postgres';      // Your DB username
$pass = 'kubem';         // Your DB password

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
        <title>Admin Order Management</title>
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
            .status-select {
                width: 100%;
                padding: 5px;
                box-sizing: border-box;
            }
        </style>
    </head>
    <body>
        <h1>Admin Order Management</h1>';

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($rows) > 0) {
        echo "<table>";
        echo "<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Location</th><th>Order Items</th><th>Order Total</th><th>Status</th><th>Created At</th><th>Action</th></tr></thead>";
        echo "<tbody>";

        foreach ($rows as $row) {
            $order_items = json_decode($row['items'], true); // Adjust field name if needed
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
            echo "<td>$" . htmlspecialchars($row['total']) . "</td>"; // Adjust field name if needed
            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
            echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
            echo "<td>
                <form method='POST' action='update_status.php'>
                    <input type='hidden' name='order_id' value='" . htmlspecialchars($row['id']) . "'>
                    <select name='status' class='status-select'>
                        <option value='Pending'" . ($row['status'] === 'Pending' ? ' selected' : '') . ">Pending</option>
                        <option value='Processing'" . ($row['status'] === 'Processing' ? ' selected' : '') . ">Processing</option>
                        <option value='Completed'" . ($row['status'] === 'Completed' ? ' selected' : '') . ">Completed</option>
                    </select>
                    <button type='submit'>Update Status</button>
                </form>
            </td>";
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
