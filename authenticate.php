<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/src/model/dbConnect.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/src/model/accountModel.php';

if(!isset($_SERVER)){
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $accountModel = new AccountModel();

    if ($accountModel->authenticateUser($email, $password)) {
        $_SESSION['user'] = $email;
        header('Location: welcome.php'); // Redirect to a welcome page after successful login
        exit;
    } else {
        $error = "Invalid email or password.";
        require 'login.php'; // Include the login form with the error message
    }
}