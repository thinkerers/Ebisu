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
     *
     * @throws \Exception If user creation fails or input is invalid.
     */
    public function create()
    {
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        if (isset($email, $password)) {
            (new \src\model\Account())->createUser($email, $password);
            $this->login($email, $password);
            exit;
        }
    }

    /**
     * Deletes the currently logged-in user account.
     *
     * Confirms the user's email to ensure they want to delete their account.
     *
     * @throws \Exception If the user is not logged in, email confirmation fails, or deletion fails.
     */
    public function delete()
    {
        // Check if the user is logged in
        if (!isset($_SESSION['user'])) {
            throw new \Exception("You are not logged in.");
        }

        // If email is not submitted yet, show the confirmation form
        if (!isset($_POST['email'])) {
            require_once('templates/account-form-delete.php');
        }

        // Confirm the email and delete the account if it matches
        if ($_POST['email'] ?? null === $_SESSION['user']) {
            (new \src\model\Account())->deleteUser($_SESSION['user']);
            $this->logout();
        } else {
            throw new \Exception("The confirmation email does not match your email.");
        }
    }

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
        if ((new \src\model\Account())->authenticateUser($filtered_email, $filtered_password)) {
            // Set session and redirect to the homepage on successful authentication
            $_SESSION['user'] = $filtered_email;
            $this->redirect('/');
        } else {
            // If authentication fails, show the account creation view
            require_once('templates/account-form-create.php');
            exit;
        }
    }

    /**
     * Logs the user out and redirects to the homepage.
     *
     * @return void
     */
    public function logout(): void
    {
        (new \src\model\Account())->logout();
        $this->redirect('/');
    }
}
