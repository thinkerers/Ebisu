<?php 
$title = 'Delete Account';
$style ='@import url(public/css/form.css);';
ob_start(); 
?>
<dialog>
  <form method="post">
    <p>Veuillez écrire votre adresse email pour confirmer la suppression du compte :</p>
    <input type="text" name="emailConfirm" required>
    <a href="" class="btnReturn">Annuler</a>
    <input name="action" type="submit" value="deleteAccount" />
  </form>
</dialog>
<button onclick="document.querySelector('dialog').showModal();">Supprimer le compte</button>
<?php
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');