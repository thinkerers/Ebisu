<?php
$title = "Oups!";
$style ='@import url(public/css/form.css);';
ob_start(); 
?>
<style>
  @import url(style.css);
</style>
<p class="book">Une erreur est survenue : <?= $errorMessage ?></p>
<?php
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');
?>