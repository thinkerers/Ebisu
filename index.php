<?php
session_start();

require_once 'bootstrap.php';

try {
    $action = $_GET['action'] ?? $_POST['action'] ?? '';

    if($action){
        $result = match ($action) {
            'login'         => $account->login(),
            'logout'        => $account->logout(),
            'createAccount' => $account->create(),
            'deleteAccount' => $account->delete(),
            default         => throw new Exception("Action inconnue."),
        };
    }
    $page->render();
} catch (Exception $e) {
    $errorMessage = $e->getMessage();

    require('templates/error.php');
}
