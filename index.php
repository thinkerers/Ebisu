<?php

require_once 'bootstrap.php';

use src\controllers as controller;

try {
    $action = $_GET['action'] ?? '';

    if ($action === '') {
        (new controller\Homepage())->execute();
        return;
    } else if ($action === 'login') {
        (new controller\Authenticate())->login();
    }
    else if ($action === 'logout') {
        (new controller\Authenticate())->logout();
    } else if ($action === 'createAccount') {
        (new controller\Account())->createAccount();
    } else {
        throw new Exception("Action inconnue.");
    }
} catch (Exception $e) {
    $errorMessage = $e->getMessage();

    require('templates/error.php');
}
