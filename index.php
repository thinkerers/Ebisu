<?php
session_start();

require_once 'bootstrap.php';

try {
    $action = $_GET['action'] ?? $_POST['action'] ?? '';

    if ($action === '') {
        $page->render();
    } else if ($action === 'login') {
        $account->login();
    }
    else if ($action === 'logout') {
        $account->logout();
    } else if ($action === 'createAccount') {
        $account->create();
    } else if ($action === 'deleteAccount') {
        $account->delete();
    }else {
        throw new Exception("Action inconnue.");
    }
} catch (Exception $e) {
    $errorMessage = $e->getMessage();

    require('templates/error.php');
}
