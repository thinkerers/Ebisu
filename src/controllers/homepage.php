<?php
namespace src\controllers;
class Homepage
{
    public function execute()
    {
        if (isset($_SESSION['user'])) {
            require($_SERVER['DOCUMENT_ROOT'].'/templates/welcome.php');
        } else {
            require($_SERVER['DOCUMENT_ROOT'].'/templates/login.php');
        }
    }
}