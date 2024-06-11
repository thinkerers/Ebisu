<?php 
session_start();
$title = 'Modifier mon compte';
ob_start(); 
?>
<style>
  /* @import url(form.css); */
  @import url(style.css);
</style>
<fieldset>
<legend>Modifier mes informations</legend>
  <label>
    <a href="changeUserEmail.php">Adresse email</a>
  </label>
  <br>
  <label>
    <a href="changeUserPassword.php">Changer le mot de passe</a>
  </label>
</fieldset>
<?php
$content = ob_get_clean();
require('layout.php');