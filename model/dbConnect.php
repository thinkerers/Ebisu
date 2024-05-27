<?php
try{
    $dbname = '';//Nom de la base de donnée
    $bdd = new PDO('mysql:host=localhost;dbname='.$dbname.';charset=utf8','root','');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    global $bdd;
}catch (Exception $e){
    die("Une erreur a été trouvé :.$e->getMessage()");
}
?>