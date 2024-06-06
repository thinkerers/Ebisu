<?php
namespace src\controllers;
class Page
{
    public function render()
    {
        if (isset($_SESSION['user'])) {
            require($_SERVER['DOCUMENT_ROOT'].'/templates/welcome.php');
        } else {
            require($_SERVER['DOCUMENT_ROOT'].'/templates/account-form-login.php');
        }
    }
}