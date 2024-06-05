<?php

namespace src\model;

class Authenticate
{
    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /');
    }

    public function login($email, $password)
    {
        require_once 'src/model/dbConnect.php';

        $query = $db->prepare('SELECT * FROM users WHERE email = :email');
        $query->execute(['email' => $email]);
        $user = $query->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        } else {
            return false;
        }
    }
}