<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/src/controllers/accountController.php';

require_once 'src/controllers/authenticateController.php';

$controller = new AccountController();
$controller->createAccount();

$action = $_GET['action'] ?? null;
try{
    if($action === 'logout'){
        $controller = new AuthenticateController();
        $controller->logout();
    }
    else{
        $controller->createAccount();
    }
}

catch (Exception $e) {
    $errorMessage = $e->getMessage();

    require('templates/error.php');
}