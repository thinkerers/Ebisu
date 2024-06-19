<?php 
$title = 'Changer le mot de passe';
ob_start(); 
?>
<style>
  @import url(style.css);
</style>
<form method="post">
  <fieldset>
  <legend>Changer de mot de passe</legend>
    <label>
      Entrez votre adresse email
      <input name="email" type="email" required>
    </label>
    <input name="request" type="submit" value="sendEmail" />
  </fieldset>
</form>
<?php 
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');