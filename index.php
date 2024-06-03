<?php

require_once 'src/controllers/homepage.php';
require_once 'src/controllers/accountController.php';
require_once 'src/controllers/authenticateController.php';


try {
    $action = $_GET['action'] ?? '';

    if ($action === '') {
        (new Homepage())->execute();
        return;
    } else if ($action === 'logout') {
        (new AuthenticateController())->logout();
    } else if ($action === 'createAccount') {
        (new AccountController())->createAccount();
    } else {
        throw new Exception("Action inconnue.");
    }
} catch (Exception $e) {
    $errorMessage = $e->getMessage();

    require('templates/error.php');
}
