<?php

require_once dirname(dirname(__FILE__)).'/model/dbConnect.php'; // Inclure le fichier de connexion à la base de données
require_once dirname(dirname(__FILE__)).'/model/accountModel.php'; // Inclure le fichier du modèle

class AccountController {

    public function createAccount() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePostRequest();
        } else {
            $this->showCreateAccountForm();
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
    public function changeEmail() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePostRequest();
        } else {
            $this->showChangeEmailForm();
        }
    }

    private function handlePostRequest() {
        #echo "On est dans handlePostRequest";
        $accountModel = new AccountModel();

        $email = $_POST['emailToConfirm']??$_POST['email']??false;
        $password = $_POST['password']??false;
        $newEmail = $_POST['newEmail'];
        $newEmail2 = $_POST['newEmail2'];

        if ($_POST['request'] == 'create') {
            if ($accountModel->createUser($email, $password)) {
                header('Location: ../success.php');
                exit();
            } else {
                $this->showError("Erreur lors de la création du compte.");
            }
        } else if ($_POST['request'] == 'delete') {
            #echo 'test';
            if ($accountModel->deleteUser($email)) {
                echo "on a supp le compte";

                header('Location: ../logout.php');
                
                exit;
            } else {
                $this->showError("Erreur lors de la suppression du compte.");
            }
        }elseif ($_POST['request'] == 'changeEmail') { //to change email
            if(!empty($newEmail) && !empty($newEmail2) && $newEmail == $newEmail2){
                if ($accountModel->changeEmail($newEmail)) {
                    echo "L'email a bien été modifié";
                    header('Location:../../logout.php');
                    exit();
                } else {
                    $this->showError("Erreur lors de la modification du compte.");
                }
            }else{
                $this->showError("Les emails ne correspondent pas");
            }
        }
    }

    private function showCreateAccountForm() {
        require_once dirname(dirname(__FILE__)).'/vue/createAccountView.php';
    }
    private function showDeleteAccountForm() {
        require_once dirname(dirname(__FILE__)).'/vue/formDelete.php';
    }
    private function showChangeEmailForm() {
        require_once dirname(dirname(__FILE__)).'/vue/changeUserEmail.php';
    }
    private function showError($error) {
        echo $error;
        #require_once dirname(dirname(__FILE__)).'/vue/createAccountView.php';
    }
}