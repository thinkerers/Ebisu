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
    public function changePassword() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePostRequest();
        } else {
            $this->showChangePasswordForm();
        }
    }

    private function handlePostRequest() {
        #echo "On est dans handlePostRequest";
        $accountModel = new AccountModel();

        $email = $_POST['emailToConfirm']??$_POST['email']??false;
        $password = $_POST['password']??false;
        $newEmail = $_POST['newEmail']??false;
        $newEmail2 = $_POST['newEmail2']??false;

        if ($_POST['request'] == 'create') {
            if($accountModel->checkEmail($email)){
                $this->showError("Un compte existe déjà avec cet email.");
            }else {
                if ($accountModel->createUser($email, $password)) {
                    header('Location: ../success.php');
                    exit();
                } else {
                    $this->showError("Erreur lors de la création du compte.");
                }
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
            if($accountModel->checkEmail($newEmail)){
                $this->showError("Un compte existe déjà avec cet email.");
            }else{
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
        }elseif ($_POST['request'] == 'changePassword') { //to change password
            $newPassword = $_POST['newPassword'];
            $newPassword2 = $_POST['newPassword2'];//Password confirmation
            if(!empty($newPassword) && !empty($newPassword2) && $newPassword == $newPassword2){
                if ($accountModel->changePassword($newPassword)) {
                    echo "Le mot de passe a bien été modifié";
                    header('Location:../../logout.php');
                    exit();
                } else {
                    $this->showError("Erreur lors de la modification du compte.");
                }
            }else{
                $this->showError("Les mots de passe ne correspondent pas");
            }
        }
        else {
            $this->showError("Erreur lors de la modification du compte.");
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
    private function showChangePasswordForm() {
        require_once dirname(dirname(__FILE__)).'/vue/changeUserPassword.php';
    }
    private function showError($error) {
        echo $error;
        #require_once dirname(dirname(__FILE__)).'/vue/createAccountView.php';
    }
}