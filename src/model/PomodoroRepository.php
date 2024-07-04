<?php

namespace src\model;

use src\lib\Logger;
use src\lib\LogLevels;

/**
 * Class PomodoroRepository
 *
 * Provides data access and manipulation operations for the "pomodoros" table.
 */
class PomodoroRepository
{
    public function __construct(
        private ?dbConnect $db = null,
        public ?PomodoroEntity $pomodoro = null,
        private ?Logger $logger = new Logger()
        ) {}

    /**
     * Logs a message to the log file.
     * 
     * @param string $message The message to log.
     * @param LogLevels $logLevel The log level to use (default: INFO).
     * @param object|null $dump The object to dump (default: the current pomodoro).
     * @throws \Exception If the object to dump is invalid.
     */
    private function logMessage(string $message, LogLevels $logLevel = LogLevels::INFO, ?object $dump = null): void
    {
        $dump ??= $this->pomodoro;

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
            $fieldMap = $this->pomodoro->fieldMap();
            $fieldsToBind = $fieldsToBind ?? array_keys($fieldMap); 

            foreach ($fieldsToBind as $field) {
                if (isset($fieldMap[$field])) {
                    // Explicitly check if the field exists on the object
                    if (property_exists($this->pomodoro, $field)) {
                        $statement->bindValue(":{$field}", $this->pomodoro->{$field}, $fieldMap[$field]);
                    } else {
                        $this->logMessage("Property '$field' does not exist on pomodoro object", LogLevels::WARNING);
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
     * Fetches a pomodoro by the pomodoro entity ID.
     *
     * @return PomodoroEntity|null The pomodoro entity found, or null if no pomodoro is found.
     * @throws \Exception If an error occurs during the database query.
     */
    public function get(): ?PomodoroEntity
    {
        $this->logMessage("Attempting to get pomodoro from database", LogLevels::INFO);

        try {
            $result = $this->executeStatement(
                'SELECT * FROM pomodoro WHERE id = :id',
                ['id']
            );

            if(!$result){
                $this->logMessage("Pomodoro not found", LogLevels::WARNING);
                return null;
            }

            $row = $result->fetchArray(SQLITE3_ASSOC);

            $this->logMessage("Pomodoro found: " . print_r($row, true), LogLevels::DEBUG);

            $pomodoro =  new UsersEntity();
            
            foreach ($this->pomodoro->fieldMap() as $field) {
                if (!isset($row[$field])) {
                    $this->logMessage("Field '$field' not found in user data", LogLevels::WARNING);
                    continue;
                }
                $pomodoro->{$field} = $row[$field];
            }

            return $pomodoro;

        } catch (\Exception $e) {
            error_log("pomodoro retrieval error: " . $e->getMessage());
            throw new \Exception("Failed to get pomodoros"); 
        }
    }

    /**
     * Fetches all pomodoros by the pomodoro entity user ID.
     *
     * @return array An array of pomodoro entities found.
     * @throws \Exception If an error occurs during the database query.
     */
    public function getAll(): array
    {
        $this->logMessage("Attempting to get all pomodoros from database", LogLevels::INFO);

        try {
            $result = $this->executeStatement(
                'SELECT * FROM pomodoro WHERE userId = :userId',
                ['userId']
            );

            if(!$result){
                $this->logMessage("Pomodoros not found", LogLevels::WARNING);
                return [];
            }

            $pomodoros = [];

            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                foreach ($this->pomodoro->fieldMap() as $field) {
                    if (!isset($row[$field])) {
                        $this->logMessage("Field '$field' not found in pomodoro data", LogLevels::WARNING);
                        continue;
                    }
                    $pomodoro =  new PomodoroEntity();
                    $pomodoro->{$field} = $row[$field];
                    $pomodoros[] = $pomodoro;
                }
            }

           return $pomodoros;

        } catch (\Exception $e) {
            error_log("pomodoros retrieval error: " . $e->getMessage());
            throw new \Exception("Les tâches n'ont pas pu être récupérées."); 
        }
    }


    /**
     * Adds a new pomodoro to the database for the current user.
     *
     * @param pomodorosEntity $pomodoro The pomodoro to be added.
     * @return pomodorosEntity|null The added pomodoro or null on failure.
     * @throws \Exception If an error occurs.
     */
    public function create(): ?PomodoroEntity
    {
        $this->logMessage("Attempting to create pomodoro", LogLevels::INFO); 
            if($this->pomodoro->isCreated()) {
                $this->logMessage("Pomodoro already exist", LogLevels::ERROR); 
                throw new \Exception("Le pomodoro existe déjà.");
            }

            if (!$this->pomodoro->isValid()) {
                $this->logMessage("Cannot create pomodoro without userId or duration", LogLevels::ERROR); 
                throw new \Exception("Le pomodoro n'est pas attribué ou n'a pas de durée.");
            }
        try {
            $this->insert();
            $this->logMessage("Pomodoro created", LogLevels::DEBUG); 
            return $this->pomodoro;

        } catch (\Exception $e) {
            error_log("Failed to create pomodoro: " . $e->getMessage()); 
            throw new \Exception("Erreur lors de la création du pomodoro."); 
        }
    }

    /**
     * Inserts a new pomodoro into the database.
     *
     * @return true If the pomodoro was inserted successfully.
     * @throws \Exception If an error occurs during the database operation.
     */
    public function insert():bool
    {
        $this->logMessage("Try to insert pomodoro", LogLevels::INFO);
        $result = $this->executeStatement(
            'INSERT INTO pomodoro (description, userId, name, urgency, priority, state, endTime) 
            VALUES (:description, :userId, :name, :urgency, :priority, :state, :endTime)',
            ['description', 'userId', 'name', 'urgency', 'priority', 'state', 'endTime']
        );

        if(!$result){
            $this->logMessage("Failed to insert pomodoro", LogLevels::ERROR);
            throw new \Exception("La tâche n'a pas pu être insérée.");
        }

        $this->pomodoro->setId($this->db->lastInsertRowID());
        return $this->pomodoro->isCreated();
    }

    /**
     * Updates the pomodoro.
     * 
     * @return bool True if the pomodoro was successfully updated.
     * @throws \Exception If there is an error during the update process.
     */
    public function update():bool
    {
        $this->logMessage("Try to update pomodoro", LogLevels::INFO);
        $result = $this->executeStatement(
            'UPDATE pomodoro 
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
            $this->logMessage("Failed to update pomodoro", LogLevels::ERROR);
            throw new \Exception("La tâche n'a pas pu être mise à jour.");
        }
        return true;
    }

    /**
     * Deletes a pomodoro from the database.
     *
     * @param pomodorosEntity $pomodoro The pomodoro object containing the ID and user ID of the pomodoro to delete.
     * @return bool True if the pomodoro was deleted successfully, false otherwise.
     * @throws \Exception If the user is not authenticated or if an error occurs during the database operation.
     */
    public function delete(): bool
    {
        $this->logMessage("Try to delete pomodoro", LogLevels::INFO);
        $result = $this->executeStatement(
            'DELETE FROM pomodoro WHERE id = :id',
            ['id']
        );
           if(!$result){
            $this->logMessage("Failed to delete pomodoro", LogLevels::ERROR);
            throw new \Exception("Le pomodoro n'a pas pu être supprimé.");
        }
            return true;
    }
}
