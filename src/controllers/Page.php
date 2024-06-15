<?php
namespace src\controllers;
class Page
{
    public function render()
    {
        if (isset($_SESSION['user'])) {
            require('templates/welcome.php');
        } else {
            require('templates/account-form-create.php');
        }
    }
}