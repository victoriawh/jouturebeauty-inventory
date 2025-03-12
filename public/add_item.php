<?php

include 'db.php';

$servername = "localhost"; // or your server name
$dbname = "jouture_beauty"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST['item_name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    // SQL query to insert data into inventory table
    $sql = "INSERT INTO inventory (item_name, category, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $item_name, $category, $quantity, $price);

    if ($stmt->execute()) {
        echo "<p class='success'>Item added successfully and updated in database!</p>";
    } else {
        echo "<p class='error'>Error: " . $stmt->error . "</p>";
    }
    
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Inventory Item</title>
    <style>
        body {
            font-family: 'Playfair Display', serif;
            background-color: #2b1406;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Ensures full height centering */
            margin: 0;
            padding: 0;
            color: #4B2E1E;
            position: relative;
            text-align: center;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: fit-content;
            margin: auto;
            background-color: lightgoldenrodyellow;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 2px solid #D4AF37;
            border-radius: 5px;
        }
        button {
            background-color: #fff;
            color: #D4AF37;
            padding: 10px;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        .success { color: green; }
        .error { color: red; }
        
        /* Logo styles */
        .logo {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 100px; /* Adjust size as needed */
        }
        .logo-left {
            left: 10px;
        }
        .logo-right {
            right: 10px;
        }
    </style>
</head>
<body>
    <!-- Logo on the top-left -->
    <img src="../assets/images/jblogo.jpg" alt="Jouture Logo" class="logo logo-left">
    


    <div class="container">
        <h2>Add Item to Inventory</h2>
        <form method="POST">
            <input type="text" name="item_name" placeholder="Item Name" required>
            <select name="category">
                <option value="Clothing">Clothing</option>
                <option value="Lip Products">Lip Products</option>
                <option value="Makeup">Makeup</option>
            </select>
            <input type="number" name="quantity" placeholder="Quantity" required>
            <input type="text" name="price" placeholder="Price" required>
            <button type="submit">Add Item</button>
        </form>
    </div>
</body>
</html>
