<?php

namespace src\model;

use src\lib\Logger;
use src\lib\LogLevels;

/**
 * Class CompendiumRepository
 *
 * Provides data access and manipulation operations for the "compendium" table.
 * Acts as a layer between the database and the application logic.
 */
class CompendiumRepository
{
    public function __construct(
        private ?dbConnect $db = null,
        public ?CompendiumEntity $compendium = null,
        private ?Logger $logger = new Logger()
        )
    {}

    /**
     * Logs a message to the log file.
     * 
     * @param string $message The message to log.
     * @param LogLevels $logLevel The log level to use (default: INFO).
     * @param object|null $dump The object to dump (default: the current compendium).
     * @throws \Exception If the object to dump is invalid.
     */
    private function logMessage(string $message, LogLevels $logLevel = LogLevels::INFO, ?object $dump = null): void
    {
        $dump ??= $this->compendium;

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
        $fieldMap = $this->compendium->fieldMap();
        $fieldsToBind = $fieldsToBind ?? array_keys($fieldMap); 

        foreach ($fieldsToBind as $field) {
            if (isset($fieldMap[$field])) {
                // Explicitly check if the field exists on the object
                if (property_exists($this->compendium, $field)) {
                    $statement->bindValue(":{$field}", $this->compendium->{$field}, $fieldMap[$field]);
                } else {
                    $this->logMessage("Property '$field' does not exist on compendium object", LogLevels::WARNING);
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
     * Fetches a compendium by the compendium entity ID.
     *
     * @return CompendiumEntity|null The compendium entity found, or null if no compendium is found.
     * @throws \Exception If an error occurs during the database query.
     */
    public function get(): ?CompendiumEntity
    {
        $this->logMessage("Attempting to get compendium from database", LogLevels::INFO);

        try {
            $result = $this->executeStatement(
                'SELECT * FROM compendiumes WHERE id = :id',
                ['id']
            );

            if(!$result){
                $this->logMessage("compendium not found", LogLevels::WARNING);
                return null;
            }

            $row = $result->fetchArray(SQLITE3_ASSOC);

            $this->logMessage("compendium found: " . print_r($row, true), LogLevels::DEBUG);

            $compendium =  new CompendiumEntity();
            
            foreach ($this->compendium->fieldMap() as $field) {
                if (!isset($row[$field])) {
                    $this->logMessage("Field '$field' not found in compendium data", LogLevels::WARNING);
                    continue;
                }
                $compendium->{$field} = $row[$field];
            }

            return $compendium;

        } catch (\Exception $e) {
            error_log("compendium retrieval error: " . $e->getMessage());
            throw new \Exception("L'entrée du compendium n'a pas été trouvée"); 
        }
    }

}
