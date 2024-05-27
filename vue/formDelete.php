<?php
//verrifier si la l'utilisateur est connecté
/*if(!isset($_SESSION['auth'])){
    header('Location: connexion.php');
    exit;
}*/
?>
<dialog>
  <form method="dialog">
  <p>Voulez-vous vraiment supprimer votre compte ?</p>
  <button>Annuler</button>
  <button>Supprimer</button>
  </form>
</dialog>
<button onclick="document.querySelector('dialog').showModal();">Supprimer le compte</button>