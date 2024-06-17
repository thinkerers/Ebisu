<?php
namespace src\controllers;
/**
 * Class Page
 * 
 * Handles page rendering and related actions.
 * And for now the pomodoro timer to.
 */
class Page
{    
    /**
     * Function that will render the page if the user is logged in or not
     *
     * @return void
     */
    public function render()
    {
        if (isset($_SESSION['user'])) {
            require('templates/welcome.php');
        } else {
            require('templates/account-form-create.php');
        }
    }
    
    /**
     * Function that will start the pomodoro timer
     *
     * @return void
     */
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
}