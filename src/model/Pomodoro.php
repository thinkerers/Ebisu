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
    public function __construct(
        public ?int $id = null, 
        public ?int $userId = null, 
        public ?string $focusTime = null, 
        public ?string $breakTime = null, 
        public ?string $startTime = null, 
        public ?string $endTime = null,
        private ?dbConnect $db = null,
        )
    {}
}
