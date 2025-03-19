<?php

session_start();

$host = "localhost";
$username = "root";
$password = "";
$dbname = "jouture_beauty";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$created_by = $_SESSION['user_id'];

$message = ""; // Message feedback variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $quantity = intval($_POST['quantity']);
    $price = floatval($_POST['price']);

    // Check if the item already exists
    $check_sql = "SELECT item_id, quantity FROM inventory WHERE name = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // If item exists, update the quantity
        $stmt->bind_result($id, $existing_quantity);
        $stmt->fetch();
        $new_quantity = $existing_quantity + $quantity;

        $update_sql = "UPDATE inventory SET quantity = ?, price = ? WHERE item_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("idi", $new_quantity, $price, $id);

        if ($update_stmt->execute()) {
            $message = "<p class='success'>New Quantity added successfully!</p>";
        } else {
            $message = "<p class='error'>Error updating item: " . $update_stmt->error . "</p>";
        }
        $update_stmt->close();
    } else {
        // If item does not exist, insert a new record
        $insert_sql = "INSERT INTO inventory (name, description, quantity, price, created_by) VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ssdis", $name, $description, $quantity, $price, $created_by);

        if ($insert_stmt->execute()) {
            $message = "<p class='success'>New item added successfully!</p>";
        } else {
            $message = "<p class='error'>Error adding item: " . $insert_stmt->error . "</p>";
        }
        $insert_stmt->close();
    }
    $stmt->close();
}

// Fetch all inventory items after update
$result = $conn->query("SELECT * FROM inventory ORDER BY item_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <style>
        body {
            font-family: 'Playfair Display', serif;
            background-color: #2b1406;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            color: #4B2E1E;
            text-align: center;
        }
        .container {
            background-color: lightgoldenrodyellow;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 350px;
        }
        input {
            width: 90%; /* Reduced width */
            padding: 6px;
            margin: 8px 0;
            border: 2px solid #D4AF37;
            border-radius: 5px;
        }
        .success { color: green; }
        .error { color: red; }
        .inventory-list {
            margin-top: 20px;
            width: 100%;
        }

	button {
            background-color: #D4AF37;
            color: #fff;
            padding: 8px;
            border: none;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
        }
	.back-button {
            margin-top: 30px;
            background-color: #D4AF37;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
	   margin-left: 1000PX
        }

       .logo{
		width: 7%;
                height: auto;
                margin-right: 1000PX;
	}

    </style>
</head>
<body>

	<!-- Jouture Logo -->
    <img src="../assets/images/jblogo.jpg" alt="Jouture Logo" class="logo">
   
    <a href="Dashboard.php" class="back-button">Back to Dashboard</a>

    <!-- Feedback Message -->
    <?php if (!empty($message)) echo $message; ?>

    <div class="container">
        <h2>Add Inventory</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Item Name" required>
            <input type="text" name="description" placeholder="Description" required>
            <input type="number" name="quantity" placeholder="Quantity" min="1" required>
            <input type="text" name="price" placeholder="Price" required>
            <button type = "submit">Add Item</button>       	    
        </form>
	
    </div>

</body>
</html>
<?php
$conn->close();
?>