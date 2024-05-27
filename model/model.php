<?php
function deleteUser($idUser){
    global $bdd;
    $deleteUser = $bdd->prepare('DELETE FROM user WHERE id = :id');
    $deleteUser->bindParam(':id', $idUser);
    $deleteUser->execute();
    //$deleteUser->closeCursor();
}
?>