<?php
require_once 'src/model/AuthenticateModel.php';

class AuthenticateController
{
    public function logout()
    {
        $model = new AuthenticateModel();
        $model->logout();
    }
}