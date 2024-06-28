<?php

namespace src\model;
/* ## Entity-Relationship Diagram

    +-------------+       +--------------+
    |  compendium |       |    fishes    |
    +-------------+       +--------------+
    | id          |<----->| compendiumId |
    | rank        |       | userId       |
    | name        |       | caughtTime   |
    | description |       | id           |
    | img         |       +--------------+
    +-------------+

    ## Description

    The `compendium` table stores each type of fishes available. Each type of fish has a unique ID, a rank, a name, a description, and an image URL.

    The `fishes` table stores the fish caught by each user. Each fishe has a unique ID, a user ID, a fish ID, and a timestamp.
*/

/**
 * Class Fishes
 *
 * Represents a record in the "fishes" table.
 *
 * @property int $id The unique identifier of the fish record.
 * @property int $userId The ID of the user who caught the fish.
 * @property int $fishId The ID of the fish in the compendium.
 * @property string $caughtTime The time when the fish was caught.
 */
class Fishes
{
    /**
     * Constructor to initialize properties.
     *
     * @param int $id
     * @param int $userId
     * @param int $fishId
     * @param string|null $caughtTime
     * @param dbConnect|null $db
     */
    public function __construct(
        public int $id, 
        public int $userId, 
        public int $fishId, 
        public ?string $caughtTime,
        private ?dbConnect $db
        )
    {}

    public function getDiscovered($userId)
    {
        try {
            $statement = $this->db->prepare('SELECT fishId FROM fishes WHERE userId = :userId');
            $statement->bindValue(':userId', $userId);
            $result = $statement->execute();
            $discoveredFish = [];
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $discoveredFish[] = $row['fishId'];
            }
            return $discoveredFish;
        } catch (\Exception $e) {
            error_log("Fish retrieval error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Stores the fish data in the database.
     *
     * @param object $fish The fish data to store.
     * @param int $userId The ID of the user who caught the fish.
     */
    public function storeFish($fish, $userId)
    {
        try {
            $statement = $this->db->prepare('INSERT INTO fishes (fishId, userId) VALUES (:fishId, :userId)');
            $statement->bindValue(':fishId', $fish->fishId);
            $statement->bindValue(':userId', $userId);
            $statement->execute();
        } catch (\Exception $e) {
            error_log("Fish storage error: " . $e->getMessage());
        }
    }
}
