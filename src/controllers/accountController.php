<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/src/model/dbConnect.php'; // Inclure le fichier de connexion à la base de données
require_once $_SERVER['DOCUMENT_ROOT'].'/src/model/accountModel.php'; // Inclure le fichier du modèle

class AccountController {

    public function createAccount() {
        $email = $_GET['email'] ?? null;
        $password = $_GET['password'] ?? null;

        if(isset($email,$password)) {
            if((new AccountModel())->createUser($email, $password)) {
                header('Location: /login.php');
                exit;
            } else {
                throw new Exception("Erreur lors de la création du compte.");
            }
        }
    }

    public function deleteAccount() {
        //echo "rentre dans deleteAccount";
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //echo "rentre dans handlePostRequest";
            $this->handlePostRequest();
        } else {
            //echo "rentre dans showDeleteAccountForm";
            $this->showDeleteAccountForm();
        }
    }

    private function handlePostRequest() {
        #echo "On est dans handlePostRequest";
        $accountModel = new AccountModel();

        $email = $_POST['emailToConfirm']??$_POST['email'];
        #echo "<hr>".$_POST['request'];

         if ($_POST['request'] == 'delete') {
            #echo 'test';
            if ($accountModel->deleteUser($email)) {
                echo "on a supp le compte";

                header('Location: ../logout.php');
                
                exit;
            } else {
                $this->showError("Erreur lors de la suppression du compte.");
            }
        }
    }

    private function showDeleteAccountForm() {
        require_once $_SERVER['DOCUMENT_ROOT'].'/templates/formDelete.php';
    }
    private function showError($error) {
        echo $error;
        #require_once dirname(dirname(__FILE__)).'/templates/createAccountView.php';
    }
}