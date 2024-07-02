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
 * @package src\model
 * @property int $id
 * @property int $userId
 * @property int $fishId
 * @property string $caughtTime
 * @property dbConnect $db
 * @property array $rarities
 * @property array $variants
 * @method array getDiscovered(int $userId)
 * @method void storeFish(object $fish, int $userId)
 * @method object getRandomRarity()
 * @method string weightedRandomChoice(array $choices)
 */
class Fishes
{
    public function __construct(
        public ?int $id = null, 
        public ?int $userId = null, 
        public ?int $fishId = null, 
        public ?string $caughtTime = null,
        public ?array $discovered = null,
        private ?dbConnect $db = null,
        private array $rarities = [
            'normal' => 70,
            'rare' => 20,
            'ultra-rare' => 9,
            'legendaire' => 1
        ],
        private array $variants = [
            'a' => 1,
            'b' => 1,
            'c' => 1,
            'd' => 1,
            'e' => 1,
            'f' => 1,
            'g' => 1,
            'h' => 1,
            'i' => 1,
        ]
        )
    {
        $this->discovered ??= $this->getDiscovered($userId); // Initialize the discovered fish array
    }

    private function getDiscovered($userId):array
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
    public function storeFish(object $fish, ?int $userId = null)
    {
        $userId ??= $this->userId;

        try {
            $statement = $this->db->prepare('INSERT INTO fishes (fishId, userId) VALUES (:fishId, :userId)');
            $statement->bindValue(':fishId', $fish->fishId);
            $statement->bindValue(':userId', $userId);
            $statement->execute();
        } catch (\Exception $e) {
            error_log("Fish storage error: " . $e->getMessage());
        }
    }

    /**
     * Determines a random rarity and variant based on weighted probabilities.
     *
     * @return object An object containing the selected rarity, variant, and fishId.
     */
    public function getRandomRarity(): object
    {
        $rarity = $this->weightedRandomChoice($this->rarities);
        $variant = $this->weightedRandomChoice($this->variants);

        $rarityIndex = array_search($rarity, array_keys($this->rarities));
        $variantIndex = array_search($variant, array_keys($this->variants));
        $fishId = $rarityIndex * 10 + $variantIndex;

        return (object)['rarity' => $rarity, 'variant' => $variant, 'fishId' => $fishId];
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
