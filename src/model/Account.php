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

    public function editEmail($email)
    {        
        try{
            $statement = $this->db->prepare('UPDATE users SET email = :email WHERE email = :oldEmail');
            $statement->bindParam(':email', $email);
            $statement->bindParam(':oldEmail', $_SESSION['user']);
            $statement->execute();
                return true;  
            }catch (\Exception $e) {
                throw new \Exception("Le mail n'a pas pu être modifié.");
            }
    }
    
    public function editPassword($password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        try{
        $statement = $this->db->prepare('UPDATE users SET hashedPassword = :hashedPassword WHERE email = :email');
        $statement->bindParam(':hashedPassword', $hashedPassword);
        $statement->bindParam(':email', $_SESSION['user']);
        $statement->execute();
            return true;   
        }catch (\Exception $e) {
            $errorMsg = "Aucun compte n'a été trouvé";
            return false; 
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
     * Retrieves the hashed password for a given email address.
     *
     * @param string $email User's email address.
     * @return string The hashed password.
     * @throws \Exception If the user is not found.
     */
    public function getUserHash($email)
    {
        try {
            $statement = $this->db->prepare('SELECT hashedPassword FROM users WHERE email = :email');
            $statement->bindValue(':email', $email, SQLITE3_TEXT);
            $result = $statement->execute();
            if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                return $row['hashedPassword'];
            } else{
                throw new \Exception("User not found.");
            }
        }
       
        catch(\Exception $e){
            error_log("User not found: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Logs the user in if valid credentials are provided.
     *
     * @param string|null $email The email address of the user.
     * @param string|null $password The password of the user.
     * @return bool True if the user is authenticated, false otherwise.
     */
    public function login($email = null, $password = null)
    {
        $hashedPassword = $this->getUserHash($email);
        return password_verify($password, $hashedPassword);
    }

}