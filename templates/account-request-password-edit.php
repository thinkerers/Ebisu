<?php 
$title = 'Changer le mot de passe';
$style ="@import url(public/css/form.css);";
ob_start(); 
?>
<form method="post">
  <fieldset>
  <legend><h2>Changer de mot de passe</h2></legend>
    <label>
      Entrez votre adresse email
      <input type="email" name="emailForPassword" required>
    </label>
    <input type="submit" name="btnSubmit" value="Envoyer"/>
  </fieldset>
</form>
<?php 
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');