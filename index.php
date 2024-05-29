<?php
require_once 'controller/accountController.php';

$controller = new AccountController();
$controller->createAccount();

$controller->deleteAccount();