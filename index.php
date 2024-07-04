<?php
require_once 'bootstrap.php';

$account = new src\controllers\Account();
$page = new src\controllers\Page();

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
        'editPassword'  => $account->editPassword(),
        'verify'        => $account->verify(),
        'startPomodoro' => $page->pomodoroTimer(), 
        'handleTasks'   => $page->handleTasks(),
        'goFishing'     => $page->goFishing(),
        default         => throw new Exception("Action inconnue."),
    };

    // Display the page after the action has been performed
    $page->render();
} catch (Exception $e) {
    $errorMessage = $e->getMessage();

    require('templates/error.php');
}
