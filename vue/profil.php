<?php 
$title = 'Modifier mon compte';
ob_start(); 
?>
<style>
  /* @import url(form.css); */
  @import url(style.css);
</style>
<fieldset>
  <legend>Profil</legend>
  <label>
    <a href="changeUserData.php">Modifier mon compte</a>
  </label>
  <br>
  <label>
    <a href="formDelete.php">Supprimer mon compte</a>
  </label>
</fieldset>
<?php
$content = ob_get_clean();
require('layout.php');