<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once __ROOT__.'/model/dbConnect.php'; // Inclure le fichier de connexion à la base de données
require_once __ROOT__.'/model/accountModel.php'; // Inclure le fichier du modèle

class AccountController {

    public function createAccount() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePostRequest();
        } else {
            $this->showCreateAccountForm();
        }
    }
    public function deleteAccount() {
        echo "rentre dans deleteAccount";
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "rentre dans handlePostRequest";
            $this->handlePostRequest();
        } else {
            $this->showDeleteAccountForm();
        }
    }

    private function handlePostRequest() {
        echo "On est dans handlePostRequest";
        $accountModel = new AccountModel();

        $email = $_POST['email'];
        $password = $_POST['password'];
        echo "<hr>".$_POST['request'];

        if ($_POST['request'] == 'create') {
            if ($accountModel->createUser($email, $password)) {
                header('Location: success.php');
                exit;
            } else {
                $this->showError("Erreur lors de la création du compte.");
            }
        } else if ($_POST['request'] == 'delete') {
            echo 'test';
            if ($accountModel->deleteUser($idUser, $email)) {
                header('Location: ./vue/createAccountView.php');
                exit;
            } else {
                $this->showError($errorMsg);
            }
        }
    }

    private function showCreateAccountForm() {
        require_once __ROOT__.'/vue/createAccountView.php';
    }
    private function showDeleteAccountForm() {
        require_once __ROOT__.'/vue/formDelete.php';
    }
    private function showError($error) {
        require_once __ROOT__.'/vue/createAccountView.php';
    }
}