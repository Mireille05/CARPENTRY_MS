<?php
// DB config
$host = 'localhost';
$dbname = 'postgres';
$username = 'postgres';
$password = 'kubem';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle delete if submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];
    $deleteSql = "DELETE FROM contact_form WHERE id = :id";
    $deleteStmt = $pdo->prepare($deleteSql);
    $deleteStmt->bindParam(':id', $deleteId, PDO::PARAM_INT);

    try {
        $deleteStmt->execute();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $e) {
        die("Error deleting message: " . $e->getMessage());
    }
}

// Fetch all messages
$sql = "SELECT * FROM contact_form ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
try {
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error retrieving data: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages - MasterCraft Woodworks</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Styles for the messages page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .btn-primary {
            background-color: #0056b3;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
        }
        .btn-primary:hover {
            background-color: #004494;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Contact Messages</h2>
        <?php if (count($messages) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
          <tbody>
    <?php foreach ($messages as $message): ?>
        <tr>
            <td><?php echo htmlspecialchars($message['id']); ?></td>
            <td><?php echo htmlspecialchars($message['name']); ?></td>
            <td><?php echo htmlspecialchars($message['email']); ?></td>
            <td><?php echo htmlspecialchars($message['subject']); ?></td>
            <td><?php echo nl2br(htmlspecialchars($message['message'])); ?></td>
            <td><?php echo htmlspecialchars($message['created_at']); ?></td>
            <td>
                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this message?');">
                    <input type="hidden" name="delete_id" value="<?php echo $message['id']; ?>">
                    <button type="submit" class="btn-primary" style="background-color: red;">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>

            </table>
        <?php else: ?>
            <p>No messages found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
