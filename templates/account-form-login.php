<?php 
$title = 'Login';
$style ='@import url(public/css/form.css);';
ob_start();
?>
<form method="post">
  <fieldset>
    <legend>Connexion</legend>
    <label>
      Email
      <input name="email" type="email" required>
    </label>
    <label>
      Mot de passe
      <input name="password" type="password">
    </label>
    <input type="submit" name="action" value="login" />
  </fieldset>
</form>
<?php
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');