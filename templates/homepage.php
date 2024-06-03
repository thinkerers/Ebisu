<?php $title = "Ebisu"; ?>

<?php ob_start(); ?>
<h1>Bienvenue sur Ebisu !</h1>
<?php require('templates/createAccountView.php'); ?>
<?php $content = ob_get_clean(); ?>

<?php require('layout.php') ?>