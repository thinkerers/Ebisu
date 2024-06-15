<?php
$title = 'Bienvenue !';
ob_start(); 
?>

<section>
<h1>Bienvenue sur Ebisu !</h1>
<a href="?action=deleteAccount">Supprimer mon compte</a>
</section>

<?php
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');
?>