<?php 
$title = 'Créer un compte';
$style ='@import url(public/css/form.css);';
ob_start(); 
?>
<form method="post">
  <fieldset>
    <legend><hgroup>
      <h2>Créez un compte</h2> 
      <span>(ou <a href="/?action=login">connectez vous</a>)</span>
    </hgroup></legend>
    <label>
      Email
      <small id="emailHint">Veuillez entrer un email valide</small>
      <input name="email" type="email" minlength="5" maxlength="50" required autofocus aria-describedby="emailHint">
    </label>
    <label>
      Mot de passe
      <small id="emailHint">(optionnel) Le mot de passe doit être de minimum 6 caractères et contenir au moins une majuscule, une minuscule, un nombre et un caractère spécial.</small>
      <input name="password" type="password" minlength="6" maxlength="50" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{6,}" aria-describedby="passwordHint">
    </label>
    <input name="action" type="submit" value="createAccount" />
  </fieldset>
</form>
<?php
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');