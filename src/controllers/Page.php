<?php
namespace src\controllers;

use \src\model\dbConnect;
use \src\model\Account as AccountModel;
use \src\model\Fishes as FishesModel;
use \src\model\Tasks as TasksModel;
class Page
{
    public function __construct(
        private ?AccountModel $accountModel = null,
        private ?dbConnect $db = null
    ) {
        $this->accountModel ??= new AccountModel();
        $this->db ??= $this->accountModel->db;
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
        $fishes = new FishesModel(
            userId: $this->accountModel->getUserId($_SESSION['user']),
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

    public function handleTasks()
    {
        if (!isset($_SESSION['user'])) {
            throw new \Exception("Vous n'êtes pas connecté.");
        }
    
        // Instantiate the Tasks model once
        $tasksModel  = new \src\model\Tasks(
            db: $this->db
        );
        // Handle form submission to add a new task
        if (isset($_POST['addTask'])) {
            $tasksModel->add($_POST['taskTitle']);
        }
    
        if(isset($_POST['removeTask'])){
            $tasksModel->delete($_POST['removeTask']);
        }
    
        $_SESSION["tasks"] = $tasksModel->get();

        $data = [
            'tasks' =>  $_SESSION["tasks"] ?? null,
        ];
    
        // Include the view template
        require_once("templates/vueToDo.php");
    }

}