<?php
require('../model/model.php');
session_start();
if($_SESSION['auth'] == false){
    //redirection page de connexion
}else{
    
if(isset($_POST['btnSuppUser'])){
    $idUser = $_SESSION['id'];
    $emailUser = $_POST['emailToConfirm'];

    $checkifUserExists = $bdd->prepare('SELECT * FROM user WHERE id = :id');
    $checkifUserExists->bindParam(':id', $idUser);
    $checkifUserExists->execute();

    if($checkifUserExists->rowCount() > 0){
        $userInfos = $checkifQuestionExists->fetch();
        if($userInfos['id'] == $_SESSION['id'] && $userInfos['email'] == $_POST['emailToConfirm']){

            deleteUser($idUser);
            //redirection vers la page d'inscription
            header('Location: ../vue/createAccountView.php');

        }else{
            $errorMsg = "Vous n'avez pas le droit de supprimer ce compte.";
        }

        }else{
            $errorMsg = "Aucun compte n'a été trouvé";
        }
    }
}