<?php
session_start();
if($_SESSION['auth'] == false){
    //redirection page de connexion
}else{

if(isset($_GET['id']) &&!empty($_GET['id'])){
    $idUser = $_GET['id'];

    $checkifUserExists = $bdd->prepare('SELECT * FROM user WHERE id = :id');
    $checkifUserExists->bindParam(':id', $idUser);
    $checkifUserExists->execute();

    if($checkifUserExists->rowCount() > 0){
        $userInfos = $checkifQuestionExists->fetch();
        if($userInfos['id'] == $_SESSION['id']){

            deleteUser($idUser);
            //redirection vers la page d'inscription

        }else{
            $errorMsg = "Vous n'avez pas le droit de supprimer ce compte.";
        }

        }else{
            $errorMsg = "Aucun compte n'a été trouvé";
        }
    

    }else{
        $errorMsg = "Aucune compte n'a été trouvé";
    }
}
?>