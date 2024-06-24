<?php

namespace src\lib;

class Pomodoro
{


/**
 * Start a new Pomodoro session.
 *
 * This function initializes a new Pomodoro session by setting the start time and duration in the session variables.
 *
 * @param int $duration The duration of the Pomodoro session in seconds.
 *
 * @return void
 */
public function start(int $duration): void
{
    $_SESSION['pomodoro-start'] = time();
    $_SESSION['pomodoro-duration'] = $duration;
}

/**
 * Destroy the current Pomodoro session.
 *
 * This function unsets the session variables related to the Pomodoro session, effectively resetting the session.
 *
 * @return void
 */
public function destroy(): void
{
    unset($_SESSION['pomodoro-start'], $_SESSION['pomodoro-duration']);
}

/**
 * Get the remaining time for the current Pomodoro session.
 *
 * This function calculates the remaining time of a Pomodoro session based on a start time and duration.
 * If the variables are not set, it returns an array indicating zero time left.
 *
 * @return array An array containing the hours, minutes, seconds left and elapsed time. 
 *               Format: [hours, minutes, seconds, elapsed time].
 */
public function getTime($start, $duration): array
{
    if(!isset($start, $duration)) {
      return [0, 0, 0, 0, 0];
    }

    $elapsed = time() - $start;
    $timeLeft = max(0, $duration - $elapsed);

    [$H, $h] = $this->splitDigits(floor($timeLeft / 3600));
    [$M, $m] = $this->splitDigits(floor(($timeLeft % 3600) / 60));
    [$S, $s] = $this->splitDigits($timeLeft % 60);

    return [$H, $h, $M, $m, $S, $s, $elapsed];
}



/**
 * Splits a given integer into its digits.
 * For single-digit numbers, a leading zero is added.
 *
 * @param int $number The integer to split.
 * @return array An array containing the digits of the number.
 */
public function splitDigits(int $number): array
{
    $digits = array_map('intval', str_split("$number"));
    return count($digits) === 1 ? [0, $digits[0]] : $digits;
}
}