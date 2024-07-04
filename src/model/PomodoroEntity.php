<?php

namespace src\model;

use DateTime;

/**
 * Class PomodoroEntity
 *
 * Represents a single Pomodoro session recorded in the "pomodoro" table.
 *
 * @property-read int $id The unique identifier of the Pomodoro session.
 * @property-read int $userId The ID of the user associated with the session.
 * @property-read string $focusTime The duration of the focus period (e.g., "25:00").
 * @property-read string $breakTime The duration of the break period (e.g., "05:00").
 * @property-read string $startTime The timestamp when the session started (ISO 8601 format).
 * @property-read string $endTime The timestamp when the session ended (ISO 8601 format).
 */
class PomodoroEntity
{
    public function __construct(
        public ?int $id = null,
        public ?int $userId = null,
        public ?DateTime $focusTime = null,
        public ?DateTime $breakTime = null,
        public ?DateTime $startTime = null,
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
            'focusTime' => SQLITE3_TEXT,
            'breakTime' => SQLITE3_TEXT,
            'startTime' => SQLITE3_TEXT,
            'endTime' => SQLITE3_TEXT
        ];
    }

    /**
     * Sets the Pomodoro session's unique identifier.
     *
     * @param int $id The Pomodoro session's ID.
     * @return $this To allow method chaining.
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Sets the ID of the user associated with the Pomodoro session.
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
     * Sets the duration of the focus period.
     *
     * @param string $focusTime The focus period duration.
     * @return $this To allow method chaining.
     */
    public function setFocusTime(string $focusTime): self
    {
        $this->focusTime = $focusTime;
        return $this;
    }

    /**
     * Sets the duration of the break period.
     *
     * @param string $breakTime The break period duration.
     * @return $this To allow method chaining.
     */
    public function setBreakTime(string $breakTime): self
    {
        $this->breakTime = $breakTime;
        return $this;
    }

    /**
     * Sets the timestamp for the start of the Pomodoro.
     *
     * @param string $startTime The start time of the session.
     * @return $this To allow method chaining.
     */
    public function setStartTime(string $startTime): self
    {
        $this->startTime = $startTime;
        return $this;
    }

    /**
     * Sets the timestamp for the end of the Pomodoro.
     *
     * @param string $endTime The end time of the session.
     * @return $this To allow method chaining.
     */
    public function setEndTime(string $endTime): self
    {
        $this->endTime = $endTime;
        return $this;
    }

    /**
     * Checks if the pomodoro has been created.
     *
     * @return bool True if the pomodoro has an ID, false otherwise.
     */
    public function isCreated(): bool
    {
        return isset($this->id);
    }


    /**
     * Checks if the pomodoro is attributed to a user.
     *
     * @return bool True if the pomodoro is attributed, false otherwise.
     */
    public function isAttributed(): bool
    {
        return isset($this->userId);
    }

    /**
     * Checks if the Pomodoro has a duration.
     *
     * @return bool True if the pomodoro has a duration, false otherwise.
     */
    public function hasDuration(): bool
    {
        return !empty($this->startTime)  && !empty($this->endTime);
    }

     /**
     * Checks if the pomodoro is attributed to a user and has a duration.
     * @return bool
     */
    public function isValid(): bool
    {
        return !empty($this->userId) && $this->hasDuration();
    }
}