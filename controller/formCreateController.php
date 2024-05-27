
<?php

class EbisuDB extends SQLite3
{
    function __construct()
    {
        $this->open('ebisu.sqlite');
    }
}

// $db = new EbisuDB();

// $db->exec('CREATE TABLE foo (bar STRING)');
// $db->exec("INSERT INTO foo (bar) VALUES ('This is a test')");

// $result = $db->query('SELECT bar FROM foo');
// var_dump($result->fetchArray());

