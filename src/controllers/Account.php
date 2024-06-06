<?php

namespace src\controllers;
use src\model as model;

class Account {

    public function createAccount() {
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        if(isset($email,$password)) {
            (new model\Account())->createUser($email, $password);
            (new Authenticate())->login($email, $password);
            exit;
        }
    }

    public function deleteAccount() {
        if(!isset($_SESSION['user'])){
            throw new \Exception("Vous n'êtes pas connecté.");
        }

        if (!isset($_POST['email'])){
            require_once('templates/account-form-delete.php');
        }
        
        if ($_POST['email']??null === $_SESSION['user']){
            (new model\Account())->deleteUser($_SESSION['user']);
            (new Authenticate())->logout();
        } else{
            throw new \Exception("L'email de confirmation ne correspond pas à votre email.");
        }
    }
}