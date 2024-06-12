<?php 
require_once '../controller/accountController.php';

$title = 'Modifier mon compte';

ob_start(); 
$controller = new AccountController();
?>
<style>
  @import url(style.css);
</style>
<fieldset>
  <legend>Profil</legend>
  <label>
    <a href="changeUserData.php">Modifier mes informations</a>
  </label>
  <br>
  <label>
    <!-- <a href="formDelete.php">Supprimer mon compte</a> -->
     <?php $controller->deleteAccount(); ?>
  </label>
</fieldset>
<?php
$content = ob_get_clean();
require('layout.php');