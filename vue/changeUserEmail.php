<?php 
$title = 'Changer le mot de passe';
ob_start(); 
?>
<style>
  @import url(style.css);
</style>
<form method="post">
  <fieldset>
    <legend>Mettre à jour votre Adresse email</legend>
    <label>
        Adresse email actuelle
        <?= $_SESSION['user'] ?>
    </label>
    <label>
        Email
        <small id="emailHint">Veuillez entrer un email valide</small>
        <input name="email" type="email" minlength="5" maxlength="50" required autofocus aria-describedby="emailHint">
    </label>
    <input name="request" type="submit" value="Envoyer"/>
  </fieldset>
</form>
<?php 
$content = ob_get_clean();
require(dirname(dirname(__FILE__)).'/vue/layout.php');
?>