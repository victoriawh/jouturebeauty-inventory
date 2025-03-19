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

// Fetch all inventory items
$result = $conn->query("SELECT * FROM inventory ORDER BY item_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Inventory</title>
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
            width: 80%;
            max-width: 700px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            border: 1px solid #4B2E1E;
            padding: 10px;
            text-align: center;
            font-size: 14px;
        }
        th {
            background-color: #D4AF37;
            color: white;
        }
        .back-button {
            margin-top: 15px;
            background-color: #D4AF37;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
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

    <div class="container">
        <h2>Inventory List</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Price ($)</th>
            </tr>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td><?= htmlspecialchars($row['quantity']) ?></td>
                    <td><?= number_format($row['price'], 2) ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No items found</td>
                </tr>
            <?php endif; ?>
        </table>

        <!-- Back Button -->
        <a href="Dashboard.php" class="back-button">Back to Dashboard</a>
    </div>

</body>
</html>
<?php
$conn->close();
?>