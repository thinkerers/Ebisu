<?php

namespace src\model;

/**
 * Class Fish
 *
 * Represents a record in the "fish" table.
 *
 * @property int $fishId The ID of the fish.
 * @property int $userId The ID of the user.
 */
class Fish
{
    /**
     * @var int
     */
    public $fishId;

    /**
     * @var int
     */
    public $userId;

    /**
     * Constructor to initialize properties.
     *
     * @param int $fishId
     * @param int $userId
     */
    public function __construct(int $fishId, int $userId)
    {
        $this->fishId = $fishId;
        $this->userId = $userId;
    }
}
