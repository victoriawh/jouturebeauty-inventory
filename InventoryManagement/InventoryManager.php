<?php
namespace App\InventoryManagement;


class InventoryManager {
    private $conn;


    public function __construct($host = "localhost", $username = "root", $password = "", $dbname = "jouture_beauty") {
        $this->conn = new \mysqli($host, $username, $password, $dbname);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }


    // Handle action dispatcher
   public function handleAction($postData = null) {
    switch ($postData['action'] ?? 'default') {
        case 'add':
            if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($postData["name"])) {
                $result = $this->addItem(
                    $postData["name"],
                    $postData["description"],
                    $postData["quantity"],
                    $postData["price"]
                );
                return $result ? "<p class='success'> Item added successfully!</p>" : "<p class='error'> Failed to add item.</p>";
            } else {
                //Return the form HTML
                return $this->addItem();
            }


        case 'delete':
            return $this->deleteItem();


        case 'search':
            return $this->searchItem();


        case 'update':
            return $this->updateItem();

        case 'view':
            return $this->viewItems();


        default:
            return "";
    }
}






//Add Item function for rendering the content
private function addItem($name = null, $description = null, $quantity = null, $price = null) {
    $output = "";
    $created_by = $_SESSION['user_id'] ?? null;

    if (!$created_by) {
        return "<p class='error'>User not authenticated.</p>";
    }

    // If form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $name && $description && $quantity && $price) {
        $stmt = $this->conn->prepare(
            "INSERT INTO inventory (name, description, quantity, price, created_by)
             VALUES (?, ?, ?, ?, ?)"
        );


        if (!$stmt) {
            $output .= "<p class='error'>Prepare failed: " . $this->conn->error . "</p>";
        } else {
            $stmt->bind_param("ssiii", $name, $description, $quantity, $price, $created_by);
            $success = $stmt->execute();


            if ($success) {
                $output .= "<p class='success'>Item added successfully!</p>";
            } else {
                $output .= "<p class='error'>Execute failed: " . $stmt->error . "</p>";
            }


            $stmt->close();
        }
    }


    // Always show the form
    $output .= <<<HTML
    <style>
        .form-container {
            margin-top: 40px;
            text-align: left;
        }
        .form-container form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .form-container label {
            font-weight: bold;
        }
        .form-container input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        h2 {
            text-align: center;
        }
        .back-btn {
                padding: 10px 25px;
                border-radius: 7px;
                font-size: 15px;
                background-color: #b8860b;
                color: white;
                text-decoration: none;
                margin-left: 600px;
            }
            .back-btn:hover {
                background-color: #a87905;
            }
        .form-container button {
            background-color: #b8860b;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #a87905;
        }
        .success {
            color: green;
            margin-top: 10px;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>


    <div class="form-container">
        <h2>Add New Inventory Item</h2>
        <form method="POST">
            <input type="hidden" name="action" value="add" />


            <label>Name</label>
            <input type="text" name="name" required />


            <label>Description</label>
            <input type="text" name="description" required />


            <label>Quantity</label>
            <input type="number" name="quantity" required />


            <label>Price</label>
            <input type="number" step="0.01" name="price" required />


            <button type="submit">Add Item</button>
        </form>
    </div>
HTML;


    return $output;
}








//Delete Item function for deleting item from inventor
private function deleteItem() {
    $output = "";


    // Get values from POST and SESSION
    $item_id = $_POST['item_id'] ?? null;
    $created_by = $_SESSION['user_id'] ?? null;


    // If the form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $item_id && $created_by) {
        $stmt = $this->conn->prepare("DELETE FROM inventory WHERE item_id = ? AND created_by = ?");
        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }


        $stmt->bind_param("ii", $item_id, $created_by);
        $success = $stmt->execute();


        if (!$success) {
            die("Execute failed: " . $stmt->error);
        }


        if ($stmt->affected_rows > 0) {
            $output .= "<p class='success'>Item deleted successfully!</p>";
        } else {
            $output .= "<p class='error'>No item found with that ID.</p>";
        }


        $stmt->close();
    }


    // Always show the form
    $output .= <<<HTML
    <style>
        .form-container {
            margin-top: 40px;
            text-align: left;
        }
        .form-container form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .form-container label {
            font-weight: bold;
        }
        .form-container input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        h2 {
            text-align: center;
        }
        .back-btn {
                padding: 10px 25px;
                border-radius: 7px;
                font-size: 15px;
                background-color: #b8860b;
                color: white;
                text-decoration: none;
                margin-left: 600px;
            }
            .back-btn:hover {
                background-color: #a87905;
            }
        .form-container button {
            background-color: #b8860b;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #a87905;
        }
    </style>


    <div class="form-container">
        <h2>Delete Inventory Item</h2>
        <form method="POST">
            <input type="hidden" name="action" value="delete" />
            <label>Enter Item ID to Delete</label>
            <input type="number" name="item_id" required />
            <button type="submit">Delete Item</button>
        </form>
    </div>
HTML;


    return $output;
}

private function searchItem(){
    $output = "";

    // Get values from POST
    $item_id = $_POST['item_id'] ?? null;
    $searchAgain = isset($_POST['search_again']);

    // If form was submitted to search for item
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $item_id && !$searchAgain) {
        $stmt = $this->conn->prepare("SELECT * FROM inventory WHERE item_id = ?");
        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("i", $item_id);
        $success = $stmt->execute();

        if (!$success) {
            die("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $item = $result->fetch_assoc();

            // Format the item information for display
            $output .= "<div class='item-details' style='margin-top: 50px;'>";
            $output .= "<h3>Item Information</h3>";
            $output .= "<p><strong>ID:</strong> " . htmlspecialchars($item['item_id']) . "</p>";
            $output .= "<p><strong>Name:</strong> " . htmlspecialchars($item['name'] ?? 'N/A') . "</p>";
            $output .= "<p><strong>Description:</strong> " . htmlspecialchars($item['description'] ?? 'N/A') . "</p>";
            $output .= "<p><strong>Quantity:</strong> " . htmlspecialchars($item['quantity'] ?? 'N/A') . "</p>";
            $output .= "<p><strong>Price:</strong> " . htmlspecialchars($item['price'] ?? 'N/A') . "</p>";
            $output .= "</div>";
        } else {
            $output .= "<p class='error' style='margin-top: 50px;'><strong>No item found with ID: " . htmlspecialchars($item_id) . "</strong></p>";
        }

        $stmt->close();

        // Show "Search Another Item" button
        $output .= <<<HTML
<style>
    .search-again-btn {
        background-color: #a87905;
        color: white;
        padding: 12px 24px;
        font-size: 18px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 20px;
    }
    .search-again-btn:hover {
        background-color: #946705;
    }
</style>

<form method="POST">
    <input type="hidden" name="search_again" value="1" />
    <button type="submit" class="search-again-btn">Search Another Item</button>
</form>
HTML;

    } else {
        // Show search form
        $output .= <<<HTML
        <style>
            .form-container {
                margin-top: 40px;
                text-align: left;
            }
            .form-container form {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }
            .form-container label {
                font-weight: bold;
            }
            .form-container input {
                padding: 8px;
                border: 1px solid #ccc;
                border-radius: 6px;
            }
            h2 {
                text-align: center;
            }
            .back-btn {
                padding: 10px 25px;
                border-radius: 7px;
                font-size: 15px;
                background-color: #b8860b;
                color: white;
                text-decoration: none;
                margin-left: 600px;
            }
            .back-btn:hover {
                background-color: #a87905;
            }
            .form-container button {
                background-color: #b8860b;
                color: white;
                padding: 10px;
                border: none;
                border-radius: 8px;
                cursor: pointer;
            }
            .form-container button:hover {
                background-color: #a87905;
            }
        </style>

        <div class="form-container">
            <h2>Search Inventory Item</h2>
            <form method="POST">
                <input type="hidden" name="action" value="search" />
                <label>Enter Item ID for the item needed.</label>
                <input type="number" name="item_id" required />
                <button type="submit">Search Item</button>
            </form>
        </div>
HTML;
    }

    return $output;
}




//View Item function for rendering the content
private function viewItems() {
    $created_by = $_SESSION['user_id'] ?? null;


    if (!$created_by) {
        return "<p class='error'>User not authenticated.</p>";
    }


    $stmt = $this->conn->prepare("SELECT item_id, name, description, quantity, price, created_at FROM inventory WHERE created_by = ?");
    $stmt->bind_param("i", $created_by);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result->num_rows === 0) {
        return "<p>No items found in your inventory.</p>";
    }


    $html = <<<HTML
    <style>
        .inventory-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }


        .inventory-table th,
        .inventory-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }


        .inventory-table th {
            background-color: #b8860b;
            color: white;
        }


        .inventory-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }


        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }


        .logo {
            width: 80px;
            height: auto;
        }


        .back-btn{
            padding: 10px 25px;
            border-radius: 7px;
            margin-left: 600px;
            font-size: 15px;
            background-color: #b8860b;
            color: white;
            text-decoration: none;
        }


        .back-btn:hover {
            background-color: #a87905;
        }


        .logout-btn {
            background-color: #8B0000;
        }


        .logout-btn:hover {
            background-color: #a52a2a;
        }
    </style>


    <h2>Inventory Listing</h2>
    <table class="inventory-table">
        <tr>
            <th>Item ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
HTML;


    while ($row = $result->fetch_assoc()) {
        $html .= "<tr>
                    <td>{$row['item_id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['description']}</td>
                    <td>{$row['quantity']}</td>
                    <td>\${$row['price']}</td>
                  </tr>";
    }


    $html .= "</table>";
    return $html;
}

// Update Item function for updating an existing inventory item
private function updateItem() {
    $output = "";
    $created_by = $_SESSION['user_id'] ?? null;

    if (!$created_by) {
        return "<p class='error'>User not authenticated.</p>";
    }

    $item_id = $_POST['item_id'] ?? null;
    $name = $_POST['name'] ?? null;
    $description = $_POST['description'] ?? null;
    $quantity = $_POST['quantity'] ?? null;
    $price = $_POST['price'] ?? null;

    // Check if form is submitted and fields are filled
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $item_id && $name && $description && $quantity && $price) {
        $stmt = $this->conn->prepare(
            "UPDATE inventory SET name = ?, description = ?, quantity = ?, price = ? 
             WHERE item_id = ? AND created_by = ?"
        );

        if (!$stmt) {
            $output .= "<p class='error'>Prepare failed: " . $this->conn->error . "</p>";
        } else {
            $stmt->bind_param("ssiiii", $name, $description, $quantity, $price, $item_id, $created_by);
            $success = $stmt->execute();

            if ($success && $stmt->affected_rows > 0) {
                $output .= "<p class='success'>Item updated successfully!</p>";
            } else {
                $output .= "<p class='error'>No item found or nothing changed.</p>";
            }

            $stmt->close();
        }
    }

    // Always show the form
    $output .= <<<HTML
    <style>
        .form-container {
            margin-top: 40px;
            text-align: left;
        }
        .form-container form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .form-container label {
            font-weight: bold;
        }
        .form-container input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        h2 {
            text-align: center;
        }
        .form-container button {
            background-color: #b8860b;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #a87905;
        }
        .success {
            color: green;
            margin-top: 10px;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>

    <div class="form-container">
        <h2>Update Inventory Item</h2>
        <form method="POST">
            <input type="hidden" name="action" value="update" />

            <label>Item ID</label>
            <input type="number" name="item_id" required />

            <label>New Name</label>
            <input type="text" name="name" required />

            <label>New Description</label>
            <input type="text" name="description" required />

            <label>New Quantity</label>
            <input type="number" name="quantity" required />

            <label>New Price</label>
            <input type="number" step="0.01" name="price" required />

            <button type="submit">Update Item</button>
        </form>
    </div>
HTML;

    return $output;
}

}

?>
