<?php

namespace src\model;
use DateTime;

/**
 * Class FishesEntity
 *
 * Represents a record of a fish caught by a user in the "fishes" table.
 * This entity is responsible for tracking the details of caught fish, 
 * determining rarity and variant, and managing interactions with the database.
 *
 * @property-read int $id The unique identifier of the caught fish record.
 * @property-read int $userId The ID of the user who caught the fish.
 * @property-read int $fishId The ID of the fish species from the compendium.
 * @property-read DateTime $caughtTime The timestamp of when the fish was caught.
 * @property-read array $discovered The list of fish species discovered by the user.
 */
class FishesEntity
{
    public function __construct(
        public ?int $id = null, 
        public ?int $userId = null, 
        public ?int $fishId = null, 
        public ?DateTime $caughtTime = null
        )
    {}

    /**
     * Returns the field map for the entity.
     *
     * @return array The field map.
     */
    public function fieldMap(): array
    {
        return [
            'id' => SQLITE3_INTEGER,
            'userId' => SQLITE3_INTEGER,
            'fishId' => SQLITE3_INTEGER,
            'caughtTime' => SQLITE3_TEXT
        ];
    }

    /**
     * Sets the fish's unique identifier.
     *
     * @param int $id The fish's ID.
     * @return $this To allow method chaining.
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Sets the user's unique identifier.
     *
     * @param int $userId The user's ID.
     * @return $this To allow method chaining.
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Sets the fish's unique identifier.
     *
     * @param int $fishId The fish's ID.
     * @return $this To allow method chaining.
     */
    public function setFishId(int $fishId): self
    {
        $this->fishId = $fishId;
        return $this;
    }

    /**
     * Sets the timestamp of when the fish was caught.
     *
     * @param DateTime $caughtTime The timestamp of when the fish was caught.
     * @return $this To allow method chaining.
     */
    public function setCaughtTime(DateTime $caughtTime): self
    {
        $this->caughtTime = $caughtTime;
        return $this;
    }

    /**
     * Checks if the fish has been created.
     *
     * @return bool True if the fish has an ID, false otherwise.
     */
    public function isCreated(): bool
    {
        return isset($this->id);
    }

     /**
     * Checks if the fish is attributed to a user and has a fishId
     * @return bool
     */
    public function isValid(): bool
    {
        return !empty($this->userId) && !empty($this->fishId);
    }
}
