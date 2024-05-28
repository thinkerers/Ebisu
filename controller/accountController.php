<?php
require_once './model/dbConnect.php'; // Inclure le fichier de connexion à la base de données
require_once './model/accountModel.php'; // Inclure le fichier du modèle

class AccountController {
    public function createAccount() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Valider les données (ajouter des contrôles de sécurité)

            // Créer une instance du modèle AccountModel
            $accountModel = new AccountModel();

            // Appeler la méthode du modèle pour insérer l'utilisateur
            if ($accountModel->createUser($email, $password)) {
                // Rediriger vers une page de succès
                header('Location: success.php');
                exit;
            } else {
                // Afficher un message d'erreur
                $error = "Erreur lors de la création du compte.";
                require_once './vue/createAccountView.php';
            }
        } else {
            // Afficher le formulaire de création de compte
            require_once './vue/createAccountView.php';
        }
    }
}
?>