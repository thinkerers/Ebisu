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
        'editEmail'     => $account->editEmail(),       //Go to the profil page
        'goToSendEmail' => $account->goToSendEmail(),   //Go to the page to send a request by email to change password
        'editPassword'  => $account->editPassword(),
        'verify'        => $account->verify(),
        'startPomodoro' => $page->pomodoroTimer(), 
        'handleTasks'       => $page->handleTasks(),
        'goFishing'     => $page->goFishing(),
        default         => throw new Exception("Action inconnue."),
    };

    // Display the page after the action has been performed
    $page->render();
} catch (Exception $e) {
    $errorMessage = $e->getMessage();

    require('templates/error.php');
}
