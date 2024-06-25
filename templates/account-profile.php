<?php 
$title = 'Modifier mon compte';
$style ='@import url(public/css/form.css);';
ob_start(); 
?>
<style>
  /* @import url(style.css); */
  @import url(form.css);
</style>
<form>
  <fieldset>
  <legend>Modifier mes informations</legend>
    <label>
      <a  href="?action=editEmail">Adresse email</a>
    </label>
    <br>
    <label>
      <a href="?action=goToSendEmail">Changer le mot de passe</a>
    </label>
    <br>
    <label>
      <a href="?action=deleteAccount">Supprimer le compte</a>
    </label>
  </fieldset>
</form>

<?php
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');
