<?php
require_once dirname(dirname(__FILE__)).'/model/dbConnect.php';


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
            $statement = $this->db->prepare('INSERT INTO users (email, hashedPassword) VALUES (:email, :hashedPassword)');
            $statement->bindValue(':email', $email);
            $statement->bindValue(':hashedPassword', $hashedPassword);

            // Exécuter la requête
            return $statement->execute();
        } catch (Exception $e) {
            // Gérer l'erreur (par exemple, la journaliser)
            return false; 
        }
    }
    public function deleteUser($email) {
        try{
        #echo "rentre dans deleteUser";
        $statement = $this->db->prepare('DELETE  FROM users WHERE email = :email');
        $statement->bindParam(':email', $email);
        $statement->execute();
            return true;   
        }catch (Exception $e) {
            // Handle the error (e.g., log it)
            $errorMsg = "Aucun compte n'a été trouvé";
            return false; 
        }
    }
    public function authenticateUser($email, $password) {
        try {
            // Prepare the SQL statement
            $statement = $this->db->prepare('SELECT hashedPassword FROM users WHERE email = :email');
            $statement->bindValue(':email', $email);
            $result = $statement->execute();
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