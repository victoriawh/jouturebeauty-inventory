<?php
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

$message = ""; // Message feedback variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $quantity = intval($_POST['quantity']);
    $price = floatval($_POST['price']);

    // Check if the item already exists
    $check_sql = "SELECT id, quantity FROM inventory WHERE name = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // If item exists, update the quantity
        $stmt->bind_result($id, $existing_quantity);
        $stmt->fetch();
        $new_quantity = $existing_quantity + $quantity;

        $update_sql = "UPDATE inventory SET quantity = ?, price = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("idi", $new_quantity, $price, $id);

        if ($update_stmt->execute()) {
            $message = "<p class='success'>Quantity updated successfully!</p>";
        } else {
            $message = "<p class='error'>Error updating item: " . $update_stmt->error . "</p>";
        }
        $update_stmt->close();
    } else {
        // If item does not exist, insert a new record
        $insert_sql = "INSERT INTO inventory (name, description, quantity, price) VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ssii", $name, $description, $quantity, $price);

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
$result = $conn->query("SELECT * FROM inventory ORDER BY id DESC");
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
        button {
            background-color: #D4AF37;
            color: #fff;
            padding: 8px;
            border: none;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
        }
        .success { color: green; }
        .error { color: red; }
        .inventory-list {
            margin-top: 20px;
            width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            border: 1px solid #4B2E1E;
            padding: 6px;
            text-align: left;
            font-size: 14px; /* Smaller text for compact view */
        }
        th {
            background-color: #D4AF37;
            color: white;
        }
    </style>
</head>
<body>

    <!-- Feedback Message -->
    <?php if (!empty($message)) echo $message; ?>

    <div class="container">
        <h2>Add or Update Inventory</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Item Name" required>
            <input type="text" name="description" placeholder="Description" required>
            <input type="number" name="quantity" placeholder="Quantity" min="1" required>
            <input type="text" name="price" placeholder="Price" required>
            <button type="submit">Add / Update Item</button>
        </form>
    </div>

    <!-- Inventory List -->
    <div class="container inventory-list">
        <h2>Inventory List</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td><?= htmlspecialchars($row['quantity']) ?></td>
                <td>$<?= number_format($row['price'], 2) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

</body>
</html>
<?php
$conn->close();
?>
