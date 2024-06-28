<?php

namespace src\model;

/**
 * Class Pomodoro
 *
 * Represents a record in the "pomodoro" table.
 *
 * @property int $id The unique identifier of the pomodoro entry.
 * @property int $userId The ID of the user who logged the pomodoro session.
 * @property string $focusTime The focus time of the pomodoro session.
 * @property string $breakTime The break time of the pomodoro session.
 * @property string $startTime The start time of the pomodoro session.
 * @property string $endTime The end time of the pomodoro session.
 */
class Pomodoro
{
    public $id;
    public $userId;
    public $focusTime;
    public $breakTime;
    public $startTime;
    public $endTime;

    /**
     * Constructor to initialize properties.
     *
     * @param int $id
     * @param int $userId
     * @param string $focusTime
     * @param string $breakTime
     * @param string $startTime
     * @param string $endTime
     */
    public function __construct(int $id, int $userId, string $focusTime, string $breakTime, string $startTime, string $endTime)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->focusTime = $focusTime;
        $this->breakTime = $breakTime;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }
}
