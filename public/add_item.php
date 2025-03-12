<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST['item_name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    
    $sql = "INSERT INTO inventory (item_name, category, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $item_name, $category, $quantity, $price);
    
    if ($stmt->execute()) {
        echo "<p class='success'>Item added successfully!</p>";
    } else {
        echo "<p class='error'>Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
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
            margin: 0;
            padding: 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            color: #4B2E1E;
            position: relative; /* Add this line to position the logo */
        }
        form {
            background-color: lightgoldenrodyellow;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            margin: auto;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 2px solid #D4AF37;
            border-radius: 5px;
        }
        button {
            background-color: lightseagreen;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        .success { color: green; }
        .error { color: red; }
        .logo {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 100px; /* Adjust size as needed */
        }
    </style>
</head>
<body>
    <img src="../assets/images/jblogo.jpg" alt="Jouture Logo" class="logo">
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
</body>
</html>
