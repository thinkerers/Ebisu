<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php'); // Redirect to login if not authenticated
    exit;
}

include_once './vue/head.php';
?>


<h1>Bienvenue sur Ebisu !</h1>
<p><a href="vue/formDelete.php">Supprimer mon compte</a></p>

<?php include_once './vue/footer.php'; ?>