<?php
namespace src\controllers;
class Page
{
    public function __construct(
        private $fishing = new \src\lib\Fishing()
    )
    {}

    public function render()
    {
        if (isset($_SESSION['user'])) {
            require('templates/welcome.php');
        } else {
            require('templates/account-form-create.php');
        }
    }

    public function pomodoroTimer(){
        if(isset($_POST['setTime'])){       
            $time = date("H:i:s",time());           //actual time     
            $timeSet = $_POST['setTime'];           //time that we get from the pomodoro
            $min = (int) substr($timeSet, -2);            //get the minutes
            $hour = (int) substr($timeSet, 0, -2);        //get the hour
            $_SESSION['min'] = $min;
            $_SESSION['hour'] = $hour;
            $_SESSION['start'] = $time;
        }
    }

    public function goFishing()
    {

        // initialize the discoveredFishes session variable if it does not exist
        if (!isset($_SESSION['discoveredFishes'])) {
            // get the discovered fishes from the database
            $_SESSION['discoveredFishes'] = $this->fishing->discovered($_SESSION['user']['id']);
        }

        $fish = $this->fishing->catch();
        $this->fishing->save($fish,$_SESSION['user']['id']);

        //update the discovered fish in the session
        $_SESSION['discoveredFishes'][$fish->fishId] = ($_SESSION['discoveredFishes'][$fish->fishId] ?? 0) + 1;

         // Prepare data for the template
        $data = [
            'fish' => $fish,
            'discoveredFishes' => $_SESSION['discoveredFishes']
        ];

            require 'templates/fishing.php';
        } 

}