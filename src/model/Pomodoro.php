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
    public function __construct(
        public int $id, 
        public int $userId, 
        public string $focusTime, 
        public string $breakTime, 
        public string $startTime, 
        public string $endTime
        )
    {}
}
