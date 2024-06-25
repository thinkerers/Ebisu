<?php 
$title = 'Changer l\'adresse email';
$style ='@import url(public/css/form.css);';
ob_start(); 
?>
<style>
  @import url(style.css);
</style>
<form method="post">
  <fieldset>
    <legend><h2>Mettre à jour votre Adresse email</h2></legend>
    <label>
        Adresse email actuelle : 
        <b><?= htmlspecialchars($_SESSION['user']); ?></b>
    </label>
    <br />
    <label>
        <small id="emailHint">Veuillez entrer un email valide</small>
        <br />
        <input name="newEmail" type="email" minlength="5" maxlength="50" required autofocus aria-describedby="emailHint">
    </label>
    <br />
    <label>
        <small id="emailHint">Confirmez l'adresse email</small>
        <br />
        <input name="newEmail2" type="email" minlength="5" maxlength="50" required autofocus aria-describedby="emailHint">
    </label>
    <input name="request" type="submit" value="changeEmail"/>
  </fieldset>
</form>
<?php 
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');