<?php 
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
    <a  href="?action=editEmail">Adresse email</a>
  </label>
  <br>
  <label>
    <a href="?action=">Changer le mot de passe</a>
  </label>
  <br>
  <label>
    <a href="?action=deleteAccount">Supprimer le compte</a>
  </label>
</fieldset>
<?php
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');
