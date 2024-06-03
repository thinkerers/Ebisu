<?php $title = "Oups!"; ?>

<?php ob_start(); ?>
<p>Une erreur est survenue : <?= $errorMessage ?></p>
<?php $content = ob_get_clean(); ?>

<?php require('layout.php') ?>