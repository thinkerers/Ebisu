<?php

namespace src\controllers;

/**
 * Class Account
 *
 * Handles account creation, deletion, login, logout, and related actions.
 */
class Account
{
        
    /**
     * Creates a new user account.
     *
     * Retrieves email and password from POST data, validates them, and attempts to create a new user.
     * If successful, automatically logs the user in.
     * @return void
     * @throws \Exception If user creation fails or input is invalid.
     */
    public function create() {
        // Filter and validate input
        $filtered_email = $this->getFilteredEmail();
        $filtered_password = $this->getFilteredPassword();
        //Check if email already exists
        if ((new \src\model\Account())->getUserHash($filtered_email)) {
            throw new \Exception("Cet email est déjà utilisé.");
        }
        // Validate input
        if($filtered_email && $filtered_password) {
            (new \src\model\Account())->create($filtered_email, $filtered_password);
            $this->login($filtered_email, $filtered_password); // Automatically log in the new user
        } else {
            throw new \Exception("Email ou mot de passe invalide."); 
        }
    }    
    /**
     * Verifies the token validity.
     *
     * @return bool true if the token is valid, false otherwise.
     * @throws \Exception If the token is not set or the verification fails.
     */
    public function verify(){
        $verificationToken = $_GET['token'];
        if (isset($verificationToken)) {
            if((new \src\model\Account())->verify($verificationToken)){
                if($_GET['action'] == 'editPassword'){
                    require_once('templates/account-edit-password.php');
                }else if($_GET['action'] == 'verify'){
                echo "Votre email a été vérifié.";
                }
                return true;
            }else{
                throw new \Exception("Erreur lors de la vérification du token.");
            }
        } else {
            throw new \Exception("Erreur lors de la récupération du Token.");
        }
    }

    /**
     * Deletes the currently logged-in user account.
     *
     * Confirms the user's email to ensure they want to delete their account.
     * @return void
     * @throws \Exception If the user is not logged in, email confirmation fails, or deletion fails.
     */
    public function delete()
    {
        // Check if the user is logged in
        if (!isset($_SESSION['user'])) {
            throw new \Exception("Vous n'êtes pas connecté.");
        }

        // If email is not submitted yet, show the confirmation form
        if (!isset($_POST['emailConfirm'])) {
            require_once('templates/account-form-delete.php');
        }

        // Confirm the email and delete the account if it matches
        if (isset($_POST['emailConfirm']) && $_POST['emailConfirm'] === $_SESSION['user']) {
            (new \src\model\Account())->delete($_SESSION['user']);
            $this->logout();
        } else {
            throw new \Exception("Le mail de confirmation ne correspond pas.");
        }
    }
    
    /**
     * Edits the profile of the currently logged-in user account.
     *
     * @return void
     */
    public function editAccount()
    {
        require_once('templates/account-profile.php');
    }
        
    /**
     * Edits the email of the currently logged-in user account.
     *
     * @return void
     * @throws \Exception If the user is not logged in, the new email is not provided, the new email does not match the confirmation, or the email is already in use.
     */
    public function editEmail()
    {
         // Check if the user is logged in
         if (!isset($_SESSION['user'])) {
            throw new \Exception("Vous n'êtes pas connecté.");
        }
         // If the new email is not submitted yet, show the form
         if (!isset($_POST['newEmail']) || !isset($_POST['newEmail2'])) {
            require_once('templates/account-form-edit-email.php');
            throw new \Exception("Vous devez fournir un email.");
        }

        if (gettype((new \src\model\Account())->getUserHash($_POST['newEmail'])) === 'string'){
            //mail already exist
            throw new \Exception("Cet email est déjà utilisé.");
        }
        
        if ($_POST['newEmail']  == $_POST['newEmail2']) {
            if((new \src\model\Account())->editEmail($_POST['newEmail'])){
                //update session
                $_SESSION['user'] = $_POST['newEmail'];
                 //redirect to home page
                 header('Location: /');
            }else {
                throw new \Exception("Erreur lors de la modification de l'email.");
            }
        }else {
            throw new \Exception("Le mail de confirmation ne correspond pas.");
        }
       
    }

    /**
     * Go to the page to send a request by email to change password.
     *
     * @return void
     * @throws \Exception If the user is not logged in, the email is not submitted yet, or the email cannot be sent.
     */
    public function goToSendEmail()
    {
            // Check if the user is logged in
            if (!isset($_SESSION['user'])) {
            throw new \Exception("Vous n'êtes pas connecté.");
        }
            // If the new email is not submitted yet, show the form
            if (!isset($_POST['emailForPassword'])) {
            require_once('templates/account-request-password-edit.php');
            throw new \Exception("Vous devez fournir un email pour pouvoir changer votre mot de passe.");
        }
        // send email if form is submitted
        else if($this->sendEmail()){
            throw new \Exception("Le mail n'a pas été envoyé."); 
        }
    }
    
    /**
     * Sends an email to the user to reset their password.
     *
     * @return void
     * @throws \Exception If the user is not logged in or the email cannot be sent, or if the mail hasn't been sent ,or the email is not the same as the session.
     */
    public function sendEmail()
    {
        // Check if the user is logged in
        if (!isset($_SESSION['user'])) {
            throw new \Exception("Vous n'êtes pas connecté.");
        }
        if(!isset($_POST['emailForPassword'])){
            require_once('templates/account-request-password-edit.php');
        }
        //Reset and get newtoken
        (new \src\model\Account())->resetTokenExpiry($_SESSION['user']);
        $token = (new \src\model\Account())->getToken($_SESSION['user']);
        //Prepare email
        $subjetEmail = 'Changer de mot de passe.';
        $messageEmail = "Cliquez sur le lien pour changer votre mot de passe : <a href= http://ebisu.test/index.php?action=editPassword&token=$token>Changer votre mot de passe</a>";
        //Check if the email is set and the same as the session
        if($_POST['emailForPassword'] === $_SESSION['user']){
            $email = $_SESSION['user'];
            if((new \src\model\Account())->sendEmail($subjetEmail, $messageEmail, $email)){
                echo "Le mail a été envoyé.";
            }else{
                throw new \Exception("Le mail n'a pas été envoyé.");
            }
        }else{
            throw new \Exception("L'email ne correspond pas.");
        }
    }   
        
    /**
     * Edits the password of the currently logged-in user account.
     *
     * @return void return a mail to confirm the password change.
     * @throws \Exception If the user is not logged in, the new password is not provided, or the new password does not match the confirmation.
     */

    public function editPassword()
    {
        if (!isset($_SESSION['user'])) {
            throw new \Exception("Vous n'êtes pas connecté.");
        }
        
        if(!isset($_POST['newPassword']) || !isset($_POST['newPassword2'])){
            require_once('templates/account-edit-password.php');
            throw new \Exception("Vous devez fournir un mot de passe.");
        }

        $subjetEmail = "Confirmation du changement de mot de passe.";
        $messageEmail = "Votre mot de passe a été modifié avec succes !";
        if(isset($_POST['newPassword']) && isset($_POST['newPassword2'])){
            if (($_POST['newPassword'] === $_POST['newPassword2']) && $this->verify()) {
                if((new \src\model\Account())->editPassword($_POST['newPassword'])){
                    //update session
                    $this->login($_SESSION['user'], $_POST['newPassword']); // Automatically log in the new user
                    //send an email to confirm the change
                    if((new \src\model\Account())->sendEmail($subjetEmail, $messageEmail, $_SESSION['user'])){
                        echo "Votre mot de passe a été modifié avec succes !";
                    }else{throw new \Exception("Le mail n'a pas été envoyé.");}
                    //redirect to home page
                    header('Location: /');
            }else{
                require_once('templates/account-request-password-edit.php');
                throw new \Exception("Les mots de passe ne correspondent pas.");
                throw new \Exception("Vérifiez vos emails.");
            }

        }else{require_once('templates/account-edit-password.php');}
    }
}

    /**
     * Logs the user in if valid credentials are provided.
     *
     * @param string|null $email The email address of the user.
     * @param string|null $password The password of the user.
     * @return int The HTTP response code.
     * @throws \Exception If an error occurs during authentication.
     */
    public function login(?string $email = null, ?string $password = null): int
    {
        // If the user is already logged in, return a 403 error: Forbidden
        if (isset($_SESSION['user'])) {
            return http_response_code(403); // Forbidden
        }

        // Filter and validate input
        $filtered_email = $this->getFilteredEmail($email);
        $filtered_password = $this->getFilteredPassword($password);

        // If input is invalid, show the login page
        if (!$filtered_email || !$filtered_password) {
            require_once('templates/account-form-login.php');
            return http_response_code(400); // Bad Request
        }

        // Authenticate the user
        if ((new \src\model\Account())->login($filtered_email, $filtered_password)) {
            $_SESSION['user'] = $filtered_email;
            return http_response_code(200); // OK
        } else {
            http_response_code(401); // Unauthorized
            header('Location: /');
        }
    }

    /**
     * Logs the user out
     *
     * @return void
     */
    public function logout(): void
    {
        (new \src\model\Account())->logout();
    }

    /**
     * Retrieves and filters the email from POST data or the provided argument.
     *
     * @param string|null $email The email address to filter.
     * @return string|null The filtered email address or null if invalid.
     */
    private function getFilteredEmail(?string $email = null): ?string
    {
        if (isset($_POST['email'])) {
            return filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        }
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Retrieves and filters the password from POST data or the provided argument.
     *
     * @param string|null $password The password to filter.
     * @return string|null The filtered password or null if invalid.
     */
    private function getFilteredPassword(?string $password = null): ?string
    {
        if (isset($_POST['password'])) {
            return filter_input(INPUT_POST, 'password', FILTER_DEFAULT);
        }
        return filter_var($password, FILTER_DEFAULT);
    }
}
