<?php
namespace App\UI;
require_once '../Authentication/Auth.php';

use App\Authentication\Auth;

session_start();

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emailInput = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $passwordInput = $_POST['password'];

    if ($emailInput && $passwordInput) {
        $auth = new Auth();
        if ($auth->login($emailInput, $passwordInput)){
		header("Location: dashboard.php");
		exit();
        } else {
            $errorMessage = "Incorrect email or password. Please try again.";
        }
    } else {
        $errorMessage = "Please ensure both email and password are entered correctly.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jouture Beauty Login</title>
    <style>
        body {
    font-family: 'Playfair Display', serif;
    background-color: #2b1406;
    color: #4B2E1E;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Header Banner */
.banner {
    background: linear-gradient(to right, #4B2E1E, #D4AF37);
    text-align: center;
    padding: 20px;
    color: #fff;
    font-size: 24px;
    font-weight: bold;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

/* Logo */
.banner img {
    width: 130px;
    height: auto;
    margin-bottom: 10px;
}

/* Login Container */
.login-container {
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    text-align: center;
    max-width: 400px;
    width: 100%;
    border: 2px solid #D4AF37;
}

.login-container h1 {
    color: #4B2E1E;
    font-size: 28px;
    margin-bottom: 20px;
    font-weight: bold;
}

/* Input Fields */
input[type="email"], input[type="password"] {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #4B2E1E;
    border-radius: 5px;
    font-size: 16px;
    background: #FAF3E0;
    color: #4B2E1E;
}

/* Login Button */
button {
    background: #D4AF37;
    color: #fff;
    border: none;
    padding: 12px 20px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    width: 100%;
    border-radius: 5px;
    transition: all 0.3s ease-in-out;
}

button:hover {
    background: #B8860B;
    transform: scale(1.05);
}

/* Error Message */
.error {
    background: #FF4C4C;
    color: white;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 15px;
    font-weight: bold;
}
        </style>
</head>
<body>
    <header class="banner">
        <img src="../assets/images/jblogo.jpg" alt="Jouture Logo"> 
        <h1>Welcome to Jouture Beauty</h1>
    </header>

    <section class="login-container">
        <h1>Login</h1>
        <?php if (isset($errorMessage) && $errorMessage !== ''): ?>
            <div class="error"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="Enter your email address" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <button type="submit">Log In</button>
        </form>
    </section>
</body>
</html>