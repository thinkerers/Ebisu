<?php

namespace src\model;

/**
 * Class Tasks
 *
 * Represents a record in the "tasks" table.
 *
 * @property int $id The unique identifier of the task.
 * @property int $userId The ID of the user who created the task.
 * @property string $description The description of the task.
 * @property string $name The name of the task.
 * @property int $urgency The urgency of the task.
 * @property int $priority The priority of the task.
 * @property int $state The state of the task.
 * @property string $endTime The end time of the task.
 */
class Tasks
{

    public function __construct(
        public ?int $id = null, 
        public ?int $userId = null, 
       public ?string $description = null, 
       public ?string $name = null, 
       public ?int $urgency = null, 
       public ?int $priority = null, 
       public ?int $state = null, 
       public ?string $endTime = null, 
       private ?dbConnect $db = null,
    )
    {}

    public function add($taskTitle = null, $taskDescription = null)
    {
        try{
            $statement = $this->db->prepare('
            INSERT INTO tasks (description, userId, name)
            VALUES (:description, (SELECT id FROM users WHERE email = :email), :name)
            ');

            $statement->bindValue(':email', $_SESSION["user"], SQLITE3_TEXT);
            $statement->bindValue(':description', $taskDescription, SQLITE3_TEXT);
            $statement->bindValue(':name', $taskTitle, SQLITE3_TEXT);
           
           $statement->execute();

            return $_SESSION["tasks"]= $taskTitle;  
            }catch (\Exception $e) {
                throw new \Exception("La tâche n'a pas pu être ajoutée.");
            }
    }
    public function get()
    {
        try{
            $statement = $this->db->prepare('SELECT name, id FROM tasks WHERE userId = (SELECT id FROM users WHERE email = :email)');
            $statement->bindValue(':email', $_SESSION["user"], SQLITE3_TEXT);
            $result = $statement->execute();
            $tasks = [];
            
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $tasks[$row['id']] = $row['name'];
            }
            return $tasks;
        } 

        catch(\Exception $e){
            error_log($e->getMessage("No tasks found."));
            return [false];
        }
    }
    public function delete()
    {
        try{
            unset($_SESSION["tasks"][$_POST['removeTask']]);
            $statement = $this->db->prepare('DELETE FROM tasks WHERE id = :id');
            $statement->bindParam(':id', $_POST['removeTask']);
            $statement->execute();
            return true;
        }
        catch (\Exception $e) {
            error_log($e->getMessage("Task not deleted"));
            return false; 
        }
    }
}
