<?php
session_start();

require_once 'bootstrap.php';

use src\controllers as controller;

try {
    $action = $_GET['action'] ?? $_POST['action'] ?? '';

    if ($action === '') {
        (new controller\Page())->execute();
        return;
    } else if ($action === 'login') {
        (new controller\Account())->login();
    }
    else if ($action === 'logout') {
        (new controller\Account())->logout();
    } else if ($action === 'createAccount') {
        (new controller\Account())->create();
    } else if ($action === 'deleteAccount') {
        (new controller\Account())->delete();
    }else {
        throw new Exception("Action inconnue.");
    }
} catch (Exception $e) {
    $errorMessage = $e->getMessage();

    require('templates/error.php');
}
