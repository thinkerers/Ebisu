<?php
namespace src\model;
class dbConnect extends \SQLite3 {
    function __construct() {
        try {
            $this->open($_SERVER['DOCUMENT_ROOT'].'/src/model/ebisu.sqlite');
        } catch (\Exception $e) {
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