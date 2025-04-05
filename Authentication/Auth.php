<?php
namespace App\Authentication;

require_once __DIR__ . '/User.php';

use App\Authentication\User;

class Auth {
    public function login(string $email, string $password){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $user = User::getByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user'] = $user['email'];
            return true;
        }

        return false;
    }

    public function isLoggedIn(){
        return isset($_SESSION['user']);
    }

    public function logout(){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_destroy();
        header("Location: login.php");
        exit();
    }
}

?>
