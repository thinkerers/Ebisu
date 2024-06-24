<?php 
$title = 'Changer le mot de passe';
ob_start(); 
?>
<style>
  @import url(style.css);
</style>
<form method="post">
  <fieldset>
    <legend>Mettre à jour votre mot de passe</legend>
    <label>
        <small id="emailHint">Veuillez entrer votre nouveau mot de passe</small>
        <br />
        <small id="emailHint">Le mot de passe doit être de minimum 6 caractères et contenir au moins une majuscule, une minuscule, un nombre et un caractère spécial.</small>
        <br />
        <input name="newPassword" type="password" minlength="6" maxlength="50" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{6,}" aria-describedby="passwordHint">
    </label>
    <br />
    <label>
        <small id="emailHint">Confirmez le mot de passe</small>
        <br />
        <input name="newPassword2" type="password" minlength="6" maxlength="50" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{6,}" aria-describedby="passwordHint">
    </label>
    <input name="request" type="submit" value="editPassword"/>
  </fieldset>
</form>
<?php 
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');