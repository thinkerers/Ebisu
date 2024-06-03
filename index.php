<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/src/controllers/accountController.php';


$controller = new AccountController();
$controller->createAccount();