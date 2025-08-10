<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MasterCraft Woodworks</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F5F5F5;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
        h2 {
            color: #333333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        label {
            display: block;
            color: #555555;
            margin-bottom: 5px;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #2E7D32;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #D84315;
        }
        .links {
            margin-top: 15px;
        }
        .links a {
            color: #2E7D32;
            text-decoration: none;
            margin: 0 10px;
        }
        .links a:hover {
            color: #D84315;
            text-decoration: underline;
        }
        footer {
            margin-top: auto;
            padding: 20px;
            background-color: #333333;
            color: black;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="images/logo.jpg" alt="MasterCraft Woodworks Logo" class="logo">
        <h2>Login</h2>
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <div class="links">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
            <p><a href="admin_login.php">Admin Login</a></p>
        </div>
    </div>
    <footer>
        <div class="container">
            <p id ="date"></p>
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