<?php

namespace src\model;

use DateTime;

/**
 * Class TasksEntity
 *
 * Represents a single task in the system.
 *
 * @property-read int $id          The unique identifier of the task.
 * @property-read int $userId      The ID of the user who owns the task.
 * @property-read string $name     The name or title of the task.
 * @property-read string $description A detailed description of the task.
 * @property-read int $urgency     A numerical value indicating the urgency of the task (higher = more urgent).
 * @property-read int $priority    A numerical value indicating the priority of the task (higher = higher priority).
 * @property-read int $state       The current state of the task (e.g., 0 = To Do, 1 = In Progress, 2 = Done).
 * @property-read DateTime $endTime  The timestamp when the task is marked as completed.
 */
class TasksEntity
{
    public function __construct(
        public ?int $id = null,
        public ?int $userId = null,
        public ?string $description = null,
        public ?string $name = null,
        public ?int $urgency = null,
        public ?int $priority = null,
        public ?bool $state = null,
        public ?DateTime $endTime = null
    ) {}

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
            'description' => SQLITE3_TEXT,
            'name' => SQLITE3_TEXT,
            'urgency' => SQLITE3_INTEGER,
            'priority' => SQLITE3_INTEGER,
            'state' => SQLITE3_INTEGER,
            'endTime' => SQLITE3_TEXT
        ];
    }

    /**
     * Sets the task's unique identifier.
     *
     * @param int $id The task's ID.
     * @return $this To allow method chaining.
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Sets the task's user identifier.
     *
     * @param int $userId The task's user ID.
     * @return $this To allow method chaining.
     */
    public function setUserUd(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Sets the task's description.
     *
     * @param string $description The task's description.
     * @return $this To allow method chaining.
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Sets the task's name.
     *
     * @param string $name The task's name.
     * @return $this To allow method chaining.
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Sets the task's urgency.
     *
     * @param int $urgency The task's urgency.
     * @return $this To allow method chaining.
     */
    public function setUrgency(int $urgency): self
    {
        $this->urgency = $urgency;
        return $this;
    }

    /**
     * Sets the task's priority.
     *
     * @param int $priority The task's priority.
     * @return $this To allow method chaining.
     */
    public function setPriority(int $priority): self
    {
        $this->priority = $priority;
        return $this;
    }

    /**
     * Sets the task's state.
     *
     * @param bool $state The task's state.
     * @return $this To allow method chaining.
     */
    public function setState(bool $state): self
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Sets the task's end time.
     *
     * @param DateTime $endTime The task's end time.
     * @return $this To allow method chaining.
     */
    public function setEndTime(DateTime $endTime): self
    {
        $this->endTime = $endTime;
        return $this;
    }

    /**
     * Checks if the task has been created in the database.
     *
     * @return bool True if the task has an ID, false otherwise.
     */
    public function isCreated(): bool
    {
        return isset($this->id);
    }

    /**
     * Checks if the task has a name.
     * @return bool
     */
    public function isValid(): bool
    {
        return !empty($this->name);
    }
}
