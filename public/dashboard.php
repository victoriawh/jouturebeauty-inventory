<?php

require '../src/auth.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jouture Beauty Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #2b1406;
            color:rgb(95, 70, 46);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .dashboard {
            width: 90%;
            max-width: 800px;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        .dashboard h1 {
            color: #b8860b;
            font-size: 28px;
        }
        .buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            margin-top: 30px;
        }
        .btn {
            background-color: #b8860b;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            font-size: 20px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .btn:hover {
            background-color: #a87905;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <h1>Welcome to Jouture Beauty Inventory</h1>
        <p>Manage your jewelry collection with ease.</p>
        <div class="buttons">
            <a href="add_item.php" class="btn">Add Item</a>
            <a href="delete_item.php" class="btn">Delete Item</a>
            <a href="search_item.php" class="btn">Search Item</a>
            <a href="update_item.php" class="btn">Update Item</a>
            <a href="view_item.php" class="btn">View Items</a>
        </div>
    </div>
</body>
</html>