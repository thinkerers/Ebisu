<?php
namespace src\controllers;

use \src\model\dbConnect;
use \src\model\UsersRepository as user;
use \src\model\FishesRepository as fish;
use \src\model\TasksRepository as task;
use \src\model\PomodoroRepository as pomodoro;
use \src\model\CompendiumRepository as compendium;
class Page
{
    public function __construct(
        private ?user $user = null,
        private ?fish $fish = null,
        private ?dbConnect $db = null
    ) {
        $this->usersModel ??= new UsersModel();
        $this->db ??= new dbConnect();
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
            userId: $this->usersModel->getUserId($_SESSION['user']),
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