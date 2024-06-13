<?php 
require_once '../controller/accountController.php';
$title = 'Changer le mot de passe';
$controller = new AccountController();
$controller->changePassword();
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
        <input name="newPassword" type="password" minlength="5" maxlength="50" required autofocus aria-describedby="emailHint">
    </label>
    <br />
    <label>
        <small id="emailHint">Confirmez le mot de passe</small>
        <br />
        <input name="newPassword2" type="password" minlength="5" maxlength="50" required autofocus aria-describedby="emailHint">
    </label>
    <input name="request" type="submit" value="changePassword"/>
  </fieldset>
</form>
<?php 
$content = ob_get_clean();
require(dirname(dirname(__FILE__)).'/vue/layout.php');
?>