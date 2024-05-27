
<?php
/**
 * Déconnecte l'utilisateur de la session en cours.
 *
 * Cette fonction détruit les données de session et efface le cookie de session,
 * déconnectant ainsi l'utilisateur.
 *
 * @return void
 */
function deconnexion()
{
    if (session_status() == PHP_SESSION_ACTIVE) {
        session_unset();
        session_destroy();
        setcookie(session_name(), '', time() - 3600, '/');  // Unset the cookie
    }

    // header('Location:' . "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"]);
    header('Location: /'); // Redirect to site root (adjust if needed)
    exit();
}

/**
 * Se connecte à la base de données et renvoie un objet PDO.
 *
 * Cette fonction crée un nouvel objet PDO et se connecte à la base de données.
 *
 * @return PDO
 */
function connectDB()
{
    $host = 'localhost';
    $dbname = 'foot';
    $login = 'root';
    $mdp = '';
    return new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $login, $mdp);
}

/**
 * Connecte l'utilisateur.
 *
 * Cette fonction connecte l'utilisateur en vérifiant l'email et le mot de passe dans la base de données.
 * Si l'email et le mot de passe correspondent, l'utilisateur est connecté et une session est créée.
 *
 * @param string $email L'adresse email de l'utilisateur.
 * @param string $password Le mot de passe de l'utilisateur.
 * @return void
 */
function login($email, $password)
{

    $req = connectDB()->prepare("SELECT mot_de_passe,id FROM `utilisateurs` WHERE email= :email");
    $req->execute(array('email' => $email));
    $reponse = $req->fetch(PDO::FETCH_ASSOC);


    $hash = $reponse["mot_de_passe"];
    $id = $reponse["id"];


    if (md5($password) !== $hash) {

        echo json_encode(array(
            "loginStatus" => array(
                "code" => "400",
                "message" => "Unable to login",
            )
        ));
        exit;
    }

    if (md5($password) === $hash) {
        $_SESSION["user"] = $id;
        echo json_encode(array(
            "loginStatus" => array(
                "code" => "200",
                "message" => "Logged in",
            )
        ));
        exit;
    }

    $req->closeCursor();
}