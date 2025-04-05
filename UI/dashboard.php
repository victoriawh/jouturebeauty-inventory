<?php

namespace App\UI;

require_once '../Authentication/Auth.php';
require_once '../InventoryManagement/InventoryManager.php';

use App\Authentication\Auth;
use App\InventoryManagement\InventoryManager;

session_start();

$auth = new Auth();
$inv = new InventoryManager();

// Check if user is logged in
if (!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit();
}
if (isset($_GET['logout'])) {
    $auth->logout();
    header("Location: login.php");
    exit();
}

$renderedContent = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"])) {
    $renderedContent = $inv->handleAction($_POST);
} elseif (isset($_GET['action']) && $_GET['action'] === 'add') {
    $renderedContent = $inv->handleAction(['action' => 'add']);
} elseif (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $renderedContent = $inv->handleAction(['action' => 'delete']);
} elseif (isset($_GET['action']) && $_GET['action'] === 'search') {
    $renderedContent = $inv->handleAction(['action' => 'search']);
} elseif (isset($_GET['action']) && $_GET['action'] === 'update') {
    $renderedContent = $inv->handleAction(['action' => 'update']);
}elseif (isset($_GET['action']) && $_GET['action'] === 'view') {
    $renderedContent = $inv->handleAction(['action' => 'view']);
}
$isAdding = isset($_GET['action']) && $_GET['action'] === 'add';
$isDeleting = isset($_GET['action']) && $_GET['action'] === 'delete';
$isSearching = isset($_GET['action']) && $_GET['action'] === 'search';
$isUpdating = isset($_GET['action']) && $_GET['action'] === 'update';
$isViewing = isset($_GET['action']) && $_GET['action'] === 'view';

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jouture Beauty Dashboard</title>
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
    .logo{
        width: 10%;
                height: auto;
                margin-right: 00PX;
    }
    .logout-btn {
        padding: 10px 25px;
                border-radius: 7px;
        font-size: 15px;
            background-color: #8B0000; /* Dark red */
        margin-left: 700PX;
    }


    .logout-btn:hover {
            background-color: #a52a2a; /* Lighter red on hover */
    }
</style>
    </head>
<body>
    <div class="dashboard">


    <!-- Jouture Logo -->
    <img src="../assets/images/jblogo.jpg" alt="Jouture Logo" class="logo">

    <!-- Back button for Each Use Case -->
    <?php if ($isAdding || $isDeleting || $isSearching || $isUpdating || $isViewing): ?>
    <a href="dashboard.php" class="btn back-btn">← Back</a>
<?php else: ?>
    <a href="?logout=true" class="btn logout-btn">Logout</a>
<?php endif; ?>

    <?php if (!empty($renderedContent)): ?>
    <?= $renderedContent ?>
<?php else: ?>
    <h1>Welcome to Jouture Beauty Inventory</h1>
    <p>Manage your jewelry collection with ease.</p>
    <div class="buttons">
        <a href="?action=add" class="btn">Add Item</a>
        <a href="?action=delete" class="btn">Delete Item</a>
        <a href="?action=search" class="btn">Search Item</a>
        <a href="?action=update" class="btn">Update Item</a>
        <a href="?action=view" class="btn">View Items</a>
    </div>
<?php endif; ?>
</body>
</html>