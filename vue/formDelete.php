<?php
//verrifier si la l'utilisateur est connecté
/*if(!isset($_SESSION['auth'])){
    header('Location: connexion.php');
    exit;
}*/
?>
<dialog>
  <form method="dialog" >
    <form method="post" action="../controller/formDeleteController.php">
  <p>Veuillez écrire votre adresse email pour confirmé la suppression du compte :</p>
  <input type="text" name="emailToConfirm" required>
  <a href="" class="btnReturn">Annuler</a>
  <input type="submit" name="btnSuppUser" value="Supprimer">
  </form>
  </form>
</dialog>
<label class="errormsg"><?php if(isset($errorMsg)){echo $errorMsg;}?></label>
<button onclick="document.querySelector('dialog').showModal();">Supprimer le compte</button>