<?php
class dbConnect extends SQLite3 {
    function __construct() {
        try {
            $this->open(dirname(dirname(__FILE__)).'/src/model/ebisu.sqlite');
        } catch (Exception $e) {
            // Log the error to a file (replace 'error_log.txt' with your desired path)
            error_log(
                date('[Y-m-d H:i:s]') . 
                " EbisuDB Error: " . $e->getMessage() . PHP_EOL,
                3, 'error_log.txt'
            ); 
            die("Error opening database. Please try again later."); 
        }
    }
}

// $db = new dbConnect();

// $db->exec('CREATE TABLE foo (bar STRING)');
// $db->exec("INSERT INTO foo (bar) VALUES ('This is a test')");

// $result = $db->query('SELECT bar FROM foo');
// var_dump($result->fetchArray());

