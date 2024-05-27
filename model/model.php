<?php
function deleteUser($idUser){
    global $bdd; //bad practice: https://www.reddit.com/r/PHP/comments/2lct7y/question_do_you_use_globals_or_not/
    $deleteUser = $bdd->prepare('DELETE FROM user WHERE id = :id');
    $deleteUser->bindParam(':id', $idUser);
    $deleteUser->execute();
    //$deleteUser->closeCursor();
}
?>