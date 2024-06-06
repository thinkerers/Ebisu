<?php

namespace src\controllers;

use src\model as model;

class Authenticate
{
    /**
     * Redirects the user to a specified URL.
     *
     * @param string $url The URL to redirect to.
     * @return void
     */
    private function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Retrieves and filters the email from POST data or the provided argument.
     *
     * @param string|null $email The email address to filter.
     * @return string|null The filtered email address or null if invalid.
     */
    private function getFilteredEmail(?string $email): ?string
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
    private function getFilteredPassword(?string $password): ?string
    {
        if (isset($_POST['password'])) {
            return filter_input(INPUT_POST, 'password', FILTER_DEFAULT);
        }
        return filter_var($password, FILTER_DEFAULT);
    }

    /**
     * Logs the user out and redirects to the homepage.
     *
     * @return void
     */
    public function logout(): void
    {
        (new model\Authenticate())->logout();
        $this->redirect('/');
    }

    /**
     * Logs the user in if valid credentials are provided.
     *
     * @param string|null $email The email address of the user.
     * @param string|null $password The password of the user.
     * @return void
     * @throws \Exception If an error occurs during authentication.
     */
    public function login(?string $email = null, ?string $password = null): void
    {
        // If the user is already logged in, redirect to the homepage
        if (isset($_SESSION['user'])) {
            $this->redirect('/');
        }

        // Filter and validate input
        $filtered_email = $this->getFilteredEmail($email);
        $filtered_password = $this->getFilteredPassword($password);

        // If input is invalid, show the login page
        if (!$filtered_email || !$filtered_password) {
            require_once('templates/account-form-login.php');
            exit;
        }

       // Authenticate the user
       if ((new model\Account())->authenticateUser($filtered_email, $filtered_password)) {
        // Set session and redirect to the homepage on successful authentication
        $_SESSION['user'] = $filtered_email;
        $this->redirect('/');
    } else {
        // If authentication fails, show the account creation view
        require_once('templates/account-form-create.php');
        exit;
    }
    }
}
