<?php 
$title = 'Delete Account';
$style ='@import url(public/css/form.css);';
ob_start(); 
?>
<dialog>
  <form method="post">
    <p>Veuillez écrire votre adresse email pour confirmer la suppression du compte :</p>
    <input type="text" name="emailConfirm" required>
    <a class="btnReturn" href="?action=editAccount">Annuler</a>
    <input name="action" type="submit" value="deleteAccount" />
  </form>
</dialog>
<script type="module">
document.querySelector('dialog').showModal();
</script>
<?php
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');