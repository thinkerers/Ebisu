<?php

spl_autoload_register(function ($class) {
    require $class . '.php';
});

$account = new src\controllers\Account();
$page = new src\controllers\Page();
