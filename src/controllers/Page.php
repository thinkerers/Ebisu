<?php
namespace src\controllers;

use \src\model\dbConnect;
use \src\model\Account;
use \src\model\Fishes;
class Page
{
    public function __construct(
        private ?Account $account = null,
        private ?dbConnect $db = null
    ) {
        $this->account ??= new Account();
        $this->db ??= $this->account->db;
    }


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
        if (!isset($_SESSION['user'])) {
            header('Location: /');
            throw new \Exception("Vous devez être connecté pour pêcher."); 
        }
        $fishes = new Fishes(
            userId: $this->account->getUserId($_SESSION['user']),
            db: $this->db
        );
        
        $_SESSION['discoveredFishes'] ??= $fishes->discovered;

        if (isset($_POST['getFish'])) {
            $catch = $fishes->getRandomRarity();
            $fishes->storeFish($catch);
            $_SESSION['discoveredFishes'][$catch->fishId] = ($_SESSION['discoveredFishes'][$catch->fishId] ?? 0) + 1;
        }
        
        $data = [
            'fish' => $catch ?? null,
            'discoveredFishes' => $_SESSION['discoveredFishes']
        ];

            require 'templates/fishing.php';
        } 

}