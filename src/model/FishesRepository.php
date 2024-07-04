<?php

namespace src\model;

use src\lib\Logger;
use src\lib\LogLevels;
use FishesRarityLevels;
use FishesVariants;

/**
 * Class FishesRepository
 *
 * Provides data access and manipulation operations for the "fishes" table.
 */
class FishesRepository
{
    public function __construct(
        private ?dbConnect $db = null,
        public ?FishesEntity $fish = null,
        private ?Logger $logger = new Logger()
        ){}
    
    /**
     * Logs a message to the log file.
     * 
     * @param string $message The message to log.
     * @param LogLevels $logLevel The log level to use (default: INFO).
     * @param object|null $dump The object to dump (default: the current fish).
     * @throws \Exception If the object to dump is invalid.
     */
    private function logMessage(string $message, LogLevels $logLevel = LogLevels::INFO, ?object $dump = null): void
    {
        $dump ??= $this->fish;

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
        $fieldMap = $this->fish->fieldMap();
        $fieldsToBind = $fieldsToBind ?? array_keys($fieldMap); 

        foreach ($fieldsToBind as $field) {
            if (isset($fieldMap[$field])) {
                // Explicitly check if the field exists on the object
                if (property_exists($this->fish, $field)) {
                    $statement->bindValue(":{$field}", $this->fish->{$field}, $fieldMap[$field]);
                } else {
                    $this->logMessage("Property '$field' does not exist on fish object", LogLevels::WARNING);
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
     * Fetches a fish by the fish entity ID.
     *
     * @return FishesEntity|null The fish entity found, or null if no fish is found.
     * @throws \Exception If an error occurs during the database query.
     */
    public function get(): ?FishesEntity
    {
        $this->logMessage("Attempting to get fish from database", LogLevels::INFO);

        try {
            $result = $this->executeStatement(
                'SELECT * FROM fishes WHERE id = :id',
                ['id']
            );

            if(!$result){
                $this->logMessage("Fish not found", LogLevels::WARNING);
                return null;
            }

            $row = $result->fetchArray(SQLITE3_ASSOC);

            $this->logMessage("Fish found: " . print_r($row, true), LogLevels::DEBUG);

            $fish =  new FishesEntity();
            
            foreach ($this->fish->fieldMap() as $field) {
                if (!isset($row[$field])) {
                    $this->logMessage("Field '$field' not found in user data", LogLevels::WARNING);
                    continue;
                }
                $fish->{$field} = $row[$field];
            }

            return $fish;

        } catch (\Exception $e) {
            error_log("Fish retrieval error: " . $e->getMessage());
            throw new \Exception("Le poisson n'a pas été trouvé"); 
        }
    }

    /**
     * Adds a new random fish to the database for the current user.
     *
     * @param FishesEntity $fish The fish to be added.
     * @return FishesEntity|null The added fish or null on failure.
     * @throws \Exception If an error occurs.
     */
    public function create(): ?FishesEntity
    {
        $this->logMessage("Attempting to create fish", LogLevels::INFO); 
            if($this->fish->isCreated()) {
                $this->logMessage("fish already exist", LogLevels::ERROR); 
                throw new \Exception("Le fish existe déjà.");
            }

            if (!$this->fish->isValid()) {
                $this->logMessage("Cannot create fish without userId or fishId", LogLevels::ERROR); 
                throw new \Exception("Le poisson n'est pas attribué ou n'est pas répertorié dans le compendium.");
            }
        try {
            $this->insert();
            $this->logMessage("fish created", LogLevels::DEBUG); 
            return $this->fish;

        } catch (\Exception $e) {
            error_log("Failed to create fish: " . $e->getMessage()); 
            throw new \Exception("Erreur lors de la création du poisson."); 
        }
    }

    /**
     * Inserts a new fish into the database.
     *
     * @return true If the fish was inserted successfully.
     * @throws \Exception If an error occurs during the database operation.
     */
    public function insert():bool
    {
        $this->logMessage("Try to insert fish", LogLevels::INFO);
        $result = $this->executeStatement(
            'INSERT INTO fishes (userId, fishId, caughtTime) 
            VALUES (:userId, :fishId, :caughtTime)',
            ['userId', 'fishId', 'caughtTime']
        );

        if(!$result){
            $this->logMessage("Failed to insert fish", LogLevels::ERROR);
            throw new \Exception("La tâche n'a pas pu être insérée.");
        }

        $this->fish->setId($this->db->lastInsertRowID());
        return $this->fish->isCreated();
    }

     /**
     * Generate a random fish based on weighted probabilities.
     *
     * @return 
     */
    public function generateFish(): FishesEntity
    {  
        $weightedRarities = [];
        foreach (FishesRarityLevels::cases() as $rarity) {
            $raritiesWithValues[$rarity->name] = $rarity->value;
        }

        $weightedVariants = [];
        foreach (FishesVariants::cases() as $variant) {
            $variantsWithValues[$variant->name] = $variant->value;
        }
        
        $rarity = $this->weightedRandomChoice($weightedRarities);
        $variant = $this->weightedRandomChoice($weightedVariants);

        $rarityIndex = array_search($rarity, array_keys($weightedRarities));
        $variantIndex = array_search($variant, array_keys($weightedVariants));

        $fishId = $rarityIndex * 10 + $variantIndex;
        $userId = $this->fish->userId;

        $fish = new FishesEntity();

        $fish
            ->setUserId($userId)
            ->setFishId($fishId)
            ->setCaughtTime(new \DateTime());

        return $fish;
    }

    /**
     * Selects a random choice from an array with weighted probabilities.
     *
     * @param array $choices An associative array where keys are choices and values are weights.
     * @return string The selected choice.
     * @throws \Exception If unable to make a choice.
     */
    public function weightedRandomChoice(array $choices): string
    {
        $totalWeight = array_sum(array_values($choices));
        $selection = random_int(1, $totalWeight);
        $count = 0;
        
        foreach ($choices as $choice => $weight) {
            $count += $weight;
            if ($count >= $selection) {
                return $choice;
            }
        }

        throw new \Exception('Unable to make a choice!');
    }
}
