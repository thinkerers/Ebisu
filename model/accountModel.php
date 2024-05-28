<?php
require_once 'dbConnect.php';

class AccountModel {
    private $db;

    function __construct() {
        $this->db = new dbConnect(); // Créer une instance de la classe de connexion
    }

    public function createUser($email, $password) {
        // Hacher le mot de passe (obligatoire pour la sécurité)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Préparer la requête SQL
            $stmt = $this->db->prepare('INSERT INTO users (email, hashedPassword) VALUES (:email, :hashedPassword)');
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':hashedPassword', $hashedPassword);

            // Exécuter la requête
            return $stmt->execute();
        } catch (Exception $e) {
            // Gérer l'erreur (par exemple, la journaliser)
            return false; 
        }
    }
    
    public function authenticateUser($email, $password) {
        try {
            // Prepare the SQL statement
            $stmt = $this->db->prepare('SELECT hashedPassword FROM users WHERE email = :email');
            $stmt->bindValue(':email', $email);
            $result = $stmt->execute();
            $row = $result->fetchArray(SQLITE3_ASSOC);

            if ($row && password_verify($password, $row['hashedPassword'])) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            // Handle the error (e.g., log it)
            return false; 
        }
    }
}
?>
