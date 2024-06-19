<?php
require_once 'bootstrap.php';

try {
    $action = match ($_GET['action'] ?? $_POST['action'] ?? '') {
        ''              => null,
        'login'         => $account->login(),
        'logout'        => $account->logout(),
        'createAccount' => $account->create(),
        'deleteAccount' => $account->delete(),
        'editAccount'   => $account->editAccount(),
        'editEmail'     => $account->editEmail(),
        'goToSendEmail' => $account->goToSendEmail(),
        'sendEmail'     => $account->sendEmail(),
        'editPassword'  => $account->editPassword(),
        'startPomodoro' => $page->pomodoroTimer(), 
        default         => throw new Exception("Action inconnue."),
    };

    // Display the page after the action has been performed
    $page->render();
} catch (Exception $e) {
    $errorMessage = $e->getMessage();

    require('templates/error.php');
}
