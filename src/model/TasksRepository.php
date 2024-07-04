<?php

namespace src\model;

use src\lib\Logger;
use src\lib\LogLevels;

/**
 * Class TasksRepository
 *
 * Provides data access and manipulation operations for the "tasks" table.
 * Acts as a layer between the database and the application logic.
 */
class TasksRepository
{
    public function __construct(
        private ?dbConnect $db = null,
        public ?TasksEntity $task = null,
        private ?Logger $logger = new Logger()
        ) {}

    private function logMessage(string $message, LogLevels $logLevel = LogLevels::INFO, ?object $dump = null): void
    {
        $dump ??= $this->task;

        if (!$dump) {
            throw new \Exception("Cannot log message without a valid Object.");
        }

        $this->logger->logMessage($message, $dump, $logLevel);
    }

    /**
     * Executes a SQL statement on the database.
     * 
     * @param string $sql The SQL statement to execute.
     * @param array|null $fieldsToBind The fields to bind to the statement (default to all).
     * @param int $resultMode The result mode to use (default: SQLITE3_ASSOC).
     * @return \SQLite3Result The result of the query.
     * @throws \Exception If an error occurs during the database operation.
    */
        private function executeStatement(string $sql, array $fieldsToBind = null):\SQLite3Result
        {
            $statement = $this->db->prepare($sql);
            
            $fieldMap = $this->task->fieldMap();

            $fieldsToBind = $fieldsToBind ?? array_keys($fieldMap); 

            foreach ($fieldsToBind as $field) {
                if (isset($fieldMap[$field])) {
                    // Explicitly check if the field exists on the object
                    if (property_exists($this->task, $field)) {
                        $statement->bindValue(":{$field}", $this->task->{$field}, $fieldMap[$field]);
                    } else {
                        $this->logMessage("Property '$field' does not exist on task object", LogLevels::WARNING);
                    }
                }
            }

            if (!$result = $statement->execute()) {
                $this->logMessage("Failed to execute statement", LogLevels::ERROR);
                throw new \Exception("Échec de l'exécution de la requête.");
            }

            return $result;
        }

    /**
     * Fetches a task by the task entity ID.
     *
     * @return TasksEntity|null The task entity found, or null if no task is found.
     * @throws \Exception If an error occurs during the database query.
     */
    public function get(): ?TasksEntity
    {
        $this->logMessage("Attempting to get task from database", LogLevels::INFO);

        try {
            $result = $this->executeStatement(
                'SELECT * FROM tasks WHERE id = :id',
                ['id']
            );

            $task = $result->fetchArray(SQLITE3_ASSOC);

            return $task ? new TasksEntity(
                id: $task['id'],
                userId: $task['userId'],
                description: $task['description'],
                name: $task['name'],
                urgency: $task['urgency'],
                priority: $task['priority'],
                state: $task['state'],
                endTime: $task['endTime']
            ) : null;

        } catch (\Exception $e) {
            error_log("Task retrieval error: " . $e->getMessage());
            throw new \Exception("Failed to get tasks"); 
        }
    }

    /**
     * Fetches all task by the task entity user ID.
     *
     * @return array An array of task entities found, or an empty array if no tasks are found.
     * @throws \Exception If an error occurs during the database query.
     */
    public function getAll(): array
    {
        $this->logMessage("Attempting to get all tasks from database", LogLevels::INFO);

        try {
            $result = $this->executeStatement(
                'SELECT * FROM tasks WHERE  userId = :userId',
                ['userId']
            );

            if(!$result){
                $this->logMessage("Pomodoros not found", LogLevels::WARNING);
                return [];
            }

            $tasks = [];

            while ($task = $result->fetchArray(SQLITE3_ASSOC)) {
                foreach ($this->task->fieldMap() as $field) {
                    if (!isset($row[$field])) {
                        $this->logMessage("Field '$field' not found in pomodoro data", LogLevels::WARNING);
                        continue;
                    }
                    $task =  new TasksEntity();
                    $task->{$field} = $row[$field];
                    $tasks[] = $task;
                }
            }

           return $tasks;

        } catch (\Exception $e) {
            error_log("Tasks retrieval error: " . $e->getMessage());
            throw new \Exception("Les tâches n'ont pas pu être récupérées."); 
        }
    }


    /**
     * Adds a new task to the database for the current user.
     *
     * @param TasksEntity $task The task to be added.
     * @return TasksEntity|null The added task or null on failure.
     * @throws \Exception If an error occurs.
     */
    public function create(): ?TasksEntity
    {
        $this->logMessage("Attempting to create task", LogLevels::INFO); 
            if($this->task->isCreated()) {
                $this->logMessage("Task already exist", LogLevels::ERROR); 
                throw new \Exception("La tâche existe déjà.");
            }

            if (!$this->task->isValid()) {
                $this->logMessage("Cannot create task without name", LogLevels::ERROR); 
                throw new \Exception("La tâche a besoin d'un nom.");
            }
        try {
            $this->insert();
            $this->logMessage("Task created", LogLevels::DEBUG); 
            return $this->task;

        } catch (\Exception $e) {
            error_log("Failed to create task: " . $e->getMessage()); 
            throw new \Exception("Erreur lors de la création de la tâche."); 
        }
    }

    /**
     * Inserts a new task into the database.
     *
     * @return true If the task was inserted successfully.
     * @throws \Exception If an error occurs during the database operation.
     */
    public function insert():bool
    {
        $this->logMessage("Try to insert task", LogLevels::INFO);
        $result = $this->executeStatement(
            'INSERT INTO tasks (description, userId, name, urgency, priority, state, endTime) 
            VALUES (:description, :userId, :name, :urgency, :priority, :state, :endTime)',
            ['description', 'userId', 'name', 'urgency', 'priority', 'state', 'endTime']
        );

        if(!$result){
            $this->logMessage("Failed to insert task", LogLevels::ERROR);
            throw new \Exception("La tâche n'a pas pu être insérée.");
        }

        $this->task->setId($this->db->lastInsertRowID());
        return $this->task->isCreated();
    }

    /**
     * Updates the task.
     * 
     * @return bool True if the task was successfully updated.
     * @throws \Exception If there is an error during the update process.
     */
    public function update():bool
    {
        $this->logMessage("Try to update task", LogLevels::INFO);
        $result = $this->executeStatement(
            'UPDATE tasks 
            SET 
                description = :description,
                name = :name,
                urgency = :urgency,
                priority = :priority,
                state = :state,
                endTime = :endTime   
            WHERE id = :id 
            AND userId = :userId'
        );
        if(!$result){
            $this->logMessage("Failed to update task", LogLevels::ERROR);
            throw new \Exception("La tâche n'a pas pu être mise à jour.");
        }
        return true;
    }

    /**
     * Deletes a task from the database.
     *
     * @param TasksEntity $task The task object containing the ID and user ID of the task to delete.
     * @return bool True if the task was deleted successfully, false otherwise.
     * @throws \Exception If the user is not authenticated or if an error occurs during the database operation.
     */
    public function delete(): bool
    {
        $this->logMessage("Try to delete task", LogLevels::INFO);
        $result = $this->executeStatement(
            'DELETE FROM tasks WHERE id = :id', ['id']
        );
           if(!$result){
            $this->logMessage("Failed to delete task", LogLevels::ERROR);
            throw new \Exception("La tâche n'a pas pu être supprimée.");
        }
            return true;
    }
}
