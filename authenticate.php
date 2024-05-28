<?php
require_once './model/dbConnect.php';
require_once './model/accountModel.php';

session_start();

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
?>
