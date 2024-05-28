<?php

require_once './model/dbConnect.php'; // Inclure le fichier de connexion à la base de données
require_once './model/accountModel.php'; // Inclure le fichier du modèle

class AccountController {
    public function createAccount() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePostRequest();
        } else {
            $this->showCreateAccountForm();
        }
    }

    private function handlePostRequest() {
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        $accountModel = new AccountModel();

        if ($accountModel->createUser($email, $password)) {
            header('Location: success.php');
            exit;
        } else {
            $this->showError("Erreur lors de la création du compte.");
        }
    }

    private function showCreateAccountForm() {
        require_once './vue/createAccountView.php';
    }

    private function showError($error) {
        require_once './vue/createAccountView.php';
    }
}