<?php

namespace src\model;

require_once $_SERVER['DOCUMENT_ROOT'].'/src/model/dbConnect.php';


class Account {
    private $db;

    function __construct() {
        $this->db = new \dbConnect(); // Créer une instance de la classe de connexion
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
        } catch (\Exception $e) {
            // Gérer l'erreur (par exemple, la journaliser)
            throw new \Exception("Erreur lors de la création du compte.");
            return false; 
        }
    }
    public function deleteUser($email) {
        if($_SESSION['user'] == $email){
        try{
        #echo "rentre dans deleteUser";
        $statement = $this->db->prepare('DELETE  FROM users WHERE email = :email');
        $statement->bindParam(':email', $email);
        $statement->execute();
            return true;   
        }catch (\Exception $e) {
            throw new \Exception("Erreur lors de la suppression du compte.");
            return false; 
        }
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
        } catch (\Exception $e) {
            // Handle the error (e.g., log it)
            return false; 
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /');
    }

    public function login($email, $password)
    {
        require_once 'src/model/dbConnect.php';

        $query = $db->prepare('SELECT * FROM users WHERE email = :email');
        $query->execute(['email' => $email]);
        $user = $query->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        } else {
            return false;
        }
    }
}