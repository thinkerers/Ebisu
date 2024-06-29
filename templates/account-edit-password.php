<?php 
$title = 'Changer le mot de passe';
$style ="@import url(public/css/form.css);";
ob_start(); 
?>
<form method="post" class="book">
  <fieldset>
    <legend><h2>Mettre à jour votre mot de passe</h2></legend>
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
    <button name="request" type="submit" value="editPassword">Valider</button>
  </fieldset>
</form>
<?php 
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');