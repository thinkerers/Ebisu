<?php

namespace src\controllers;
use src\model as model;

class Authenticate
{
    public function logout()
    {
        $model = new model\Authenticate();
        $model->logout();
    }

    public function login()
    {
        $email = filter_input(INPUT_GET, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_GET, 'password');

        if (!$email || !$password) {
            throw new \Exception("Email et mot de passe requis.");
        }

        if ((new model\Account())->authenticateUser($email, $password)) {
            if(!isset($_SERVER)){
                session_start();
            }
            $_SESSION['user'] = $email;
            header('Location: welcome.php');
            exit;
        }  else {
            throw new \Exception("Email ou mot de passe incorrect.");
        }
    }
}






