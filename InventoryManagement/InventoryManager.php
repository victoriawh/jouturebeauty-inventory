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
                return $this->add_item();
            }

        case 'delete':
            if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($postData["item_id"])) {
                $item_id = intval($postData["item_id"]);
                $result = $this->deleteItem($item_id);
                return $result ? "<p class='success'>Item deleted successfully!</p>" : "<p class='error'>Failed to delete item.</p>";
            } else {
                return $this->delete_item_form();
            }

        case 'search':

        case 'update':

        case 'view':
            return $this->viewItems();

        default:
            return "";
    }
}

   private function add_item() {
    return <<<HTML
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
            h2{
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
}

private function addItem($name, $description, $quantity, $price) {
    $created_by = $_SESSION['user_id'] ?? null;

    if (!$created_by) {
        die("User not authenticated.");
    }

    $stmt = $this->conn->prepare(
        "INSERT INTO inventory (name, description, quantity, price, created_by) 
         VALUES (?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("ssiii", $name, $description, $quantity, $price, $created_by);

    $success = $stmt->execute();
    if (!$success) {
        die("Execute failed: " . $stmt->error);
    }

    $stmt->close();
    return $success;
}

//Delete Item function for deleting item from inventory

private function delete_item_form() {
    return <<<HTML
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
}

private function deleteItem($item_id) {
    $created_by = $_SESSION['user_id'] ?? null;

    if (!$created_by) {
        return false;
    }

    $stmt = $this->conn->prepare("DELETE FROM inventory WHERE item_id = ? AND created_by = ?");
    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("ii", $item_id, $created_by);

    $success = $stmt->execute();
    if (!$success) {
        die("Execute failed: " . $stmt->error);
    }

    $stmt->close();
    return $success;
}


//View Item function for rendering the content
private function viewItems() {
    $created_by = $_SESSION['user_id'] ?? null;

    if (!$created_by) {
        return "<p class='error'>User not authenticated.</p>";
    }

    $stmt = $this->conn->prepare("SELECT name, description, quantity, price, created_at FROM inventory WHERE created_by = ?");
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
            <th>Name</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
HTML;

    while ($row = $result->fetch_assoc()) {
        $html .= "<tr>
                    <td>{$row['name']}</td>
                    <td>{$row['description']}</td>
                    <td>{$row['quantity']}</td>
                    <td>\${$row['price']}</td>
                  </tr>";
    }

    $html .= "</table>";
    return $html;
}


}

?>
