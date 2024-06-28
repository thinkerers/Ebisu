<?php

namespace src\model;

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
     * @param string $caughtTime
     */
    public function __construct(
        public int $id, 
        public int $userId, 
        public int $fishId, 
        public ?string $caughtTime
        )
    {}
}
