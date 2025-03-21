<?php
namespace App\Authentication;
use App\Authentication\User;

class Auth{

    public function login(string $email, string $password): bool{
        session_start();
        $_SESSION['user'] = $email;
        return true;
    }

    public function isLoggedIn(): bool{
        return isset($_SESSION['user']);
    }

    public function logout(): void{
        session_start();
        session_destroy();
        header("Location: login.php"):
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jouture Beauty Login</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
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