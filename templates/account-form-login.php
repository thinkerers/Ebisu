<?php 
$title = 'Login';
$style ='@import url(public/css/form.css);';
$transition="start";

ob_start();
?>
<form method="post"  class="book">
  <fieldset>
    <legend><h2>Connexion</h2></legend>
    <label>
      Email
      <input name="email" type="email" required>
    </label>
    <label>
      Mot de passe
      <input name="password" type="password">
    </label>
    <button type="submit" name="action" value="login">Valider</button>
  </fieldset>
</form>
<?php
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');