<?php

namespace src\model;

/**
 * Class Tasks
 *
 * Represents a record in the "tasks" table.
 *
 * @property int $id The unique identifier of the task.
 * @property int $userId The ID of the user who created the task.
 * @property string $description The description of the task.
 * @property string $name The name of the task.
 * @property int $urgency The urgency of the task.
 * @property int $priority The priority of the task.
 * @property int $state The state of the task.
 * @property string $endTime The end time of the task.
 */
class Tasks
{
    public $id;
    public $userId;
    public $description;
    public $name;
    public $urgency;
    public $priority;
    public $state;
    public $endTime;

    /**
     * Constructor to initialize properties.
     *
     * @param int $id
     * @param int $userId
     * @param string $description
     * @param string $name
     * @param int $urgency
     * @param int $priority
     * @param int $state
     * @param string $endTime
     */
    public function __construct(int $id, int $userId, string $description, string $name, int $urgency, int $priority, int $state, string $endTime)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->description = $description;
        $this->name = $name;
        $this->urgency = $urgency;
        $this->priority = $priority;
        $this->state = $state;
        $this->endTime = $endTime;
    }
}
