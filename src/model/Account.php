<?php

namespace src\model;

/**
 * Class Account
 *
 * Handles account-related operations (creation, deletion, login, logout).
 */
class Account
{
    /**
     * @var \dbConnect Database connection object.
     */
    private $db;

    /**
     * Account constructor.
     * Initializes the database connection.
     */
    public function __construct()
    {
        $this->db = new dbConnect();
    }

    /**
     * Creates a new user account.
     *
     * @param string $email User's email address.
     * @param string $password User's password (plain text).
     * @return bool True on success, false on failure.
     * @throws \Exception If an error occurs during account creation.
     */
    public function create($email, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $statement = $this->db->prepare('INSERT INTO users (email, hashedPassword) VALUES (:email, :hashedPassword)');
            $statement->bindValue(':email', $email);
            $statement->bindValue(':hashedPassword', $hashedPassword);
            return $statement->execute();
        } catch (\Exception $e) {
            error_log("Account creation error: " . $e->getMessage());
            throw new \Exception("Erreur lors de la création du compte.");
        }
    }

    /**
     * Delete the account linked to the email passed as argument
     *
     * @param string $email the email of the account to delete
     * @return bool True if the account has been deleted, false otherwise.
     */
    public function delete($email)
    {
        if ($_SESSION['user'] == $email) {
            try {
                $statement = $this->db->prepare('DELETE FROM users WHERE email = :email');
                $statement->bindParam(':email', $email);
                $statement->execute();
                return true;
            } catch (\Exception $e) {
                error_log("Account delete error: " . $e->getMessage());
                throw new \Exception("Erreur lors de la suppression du compte.");
            }
        }
    }

    /**
     * Logs out the current user.
     *
     * Destroys the session and redirects to the home page.
     */
    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /');
    }

 /**
 * Logs in a user.
 *
 * @param string|null $email User's email address.
 * @param string|null $password User's password (plain text).
 * @return bool True on successful login, false on failure.
 */
public function login($email = null, $password = null)
{
    try {
        $statement = $this->db->prepare('SELECT hashedPassword FROM users WHERE email = :email');
        $statement->bindValue(':email', $email, SQLITE3_TEXT);
        $result = $statement->execute();

        if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            if (password_verify($password, $row['hashedPassword'])) {
                return true;
            }
        }
        
        return false;
    } catch (\Exception $e) {
        // Log the error (important for debugging)
        error_log("Login error: " . $e->getMessage());
        return false;
    }
}

}