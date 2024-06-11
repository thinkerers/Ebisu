<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php'); // Redirect to login if not authenticated
    exit;
}

$title = 'Bienvenue !';
ob_start(); 
?>

<section>
<h1>Bienvenue sur Ebisu !</h1>
</section>

<?php
$content = ob_get_clean();
require('vue/layout.php');
?>