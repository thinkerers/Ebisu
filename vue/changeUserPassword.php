<?php 
session_start();
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
    <input name="request" type="submit" value="Envoyer" />
  </fieldset>
</form>
<?php 
$content = ob_get_clean();
require(dirname(dirname(__FILE__)).'/vue/layout.php');
?>