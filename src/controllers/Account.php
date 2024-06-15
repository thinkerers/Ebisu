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

    public function editAccount()
    {
        require_once('templates/account-profile.php');
    }

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
            throw new \Exception("Email already exists.");
        }
        
        if (isset($_POST['newEmail'])  === isset($_POST['newEmail2'])) {
            if((new \src\model\Account())->editEmail($_POST['newEmail'])){
                //update session
                $_SESSION['user'] = $_POST['newEmail'];
                 //redirect to home page
                 header('Location: /');
            }
               
            else {
                throw new \Exception("Le mail de confirmation ne correspond pas.");
            }
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
