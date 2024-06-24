<?php 
$title = 'Changer le mot de passe';
ob_start(); 
?>
<form method="post">
  <fieldset>
  <legend>Changer de mot de passe</legend>
    <label>
      Entrez votre adresse email
      <input type="email" name="emailForPassword" required>
    </label>
    <input type="submit"/>
  </fieldset>
</form>
<?php 
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');