<?php
require_once '../controller/accountController.php';

$controller = new AccountController();
$controller->deleteAccount();
?>
<dialog>
  <form method="post">
    <p>Veuillez écrire votre adresse email pour confirmé la suppression du compte :</p>
    <input type="text" name="emailToConfirm" required>
    <a href="" class="btnReturn">Annuler</a>
    <input name="request" type="submit" value="delete" />
  </form>
    <label class="errormsg"><?php if(isset($errorMsg)){echo $errorMsg;}?></label>
</dialog>
<button onclick="document.querySelector('dialog').showModal();">Supprimer le compte</button>