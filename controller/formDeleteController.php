<?php
require('../model/model.php');
session_start();
if($_SESSION['auth'] == false){
    //redirection page de connexion
}else{
    
if(isset($_POST['btnSuppUser'])){
    $idUser = $_SESSION['id'];
    $emailUser = $_POST['emailToConfirm'];

    
    }
}