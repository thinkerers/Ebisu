<?php

class AuthenticateModel 
{
    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /');
    }
}