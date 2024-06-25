<?php 
$title = 'Changer le mot de passe';
$style ="@import url(public/css/form.css);";
ob_start(); 
?>
<style>
  @import url(style.css);
</style>
<form method="post">
  <fieldset>
  <legend><h2>Changer de mot de passe</h2></legend>
    <label>
      Entrez votre adresse email
      <input name="email" type="email" required>
    </label>
    <button name="request" type="submit" value="editPassword">Valider</button>
  </fieldset>
</form>
<?php 
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');