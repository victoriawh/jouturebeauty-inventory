<?php
namespace App\Authentication;

class User{
    private $userID;
    private $firstName;
    private $lastName;
    private $password;
    private $email:
    private $role;
    private $created_at;

    public fucntion __construct($userID, $firstName, $lastName, $password, $email, $role, $created_at){
        $this->userID = $userID;
        $this->firstName= $firstName;
        $this->lastName = $lastName;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->email = $email;
        $this->role = $role;
        $this->created_at = $created_at;
    }

    public function getFullName(): string{
        return $this->firstName . " " . $this->lastName;
    }

    public function getPassword(): string{
        return $this->password;
    }

    public function getRole(): string{
        return $this->role;
    }

    public function getEmail(): string{
        return $this->email;
    }

    public function getCreatedAt(): string{
        return $this->created_at;
    }

    public function verifyPassword(string $inputPassword): bool{
        return password_verify($inputPassword, $this->password);
    }
}

?>