<?php

namespace src\model;

/**
 * Class Users
 *
 * Represents a record in the "users" table.
 *
 * @property int $id The unique identifier of the user.
 * @property string $email The email address of the user.
 * @property string $hashedPassword The hashed password of the user.
 */
class Users
{
    public $id;
    public $email;
    public $hashedPassword;

    /**
     * Constructor to initialize properties.
     *
     * @param int $id
     * @param string $email
     * @param string $hashedPassword
     */
    public function __construct(int $id, string $email, string $hashedPassword)
    {
        $this->id = $id;
        $this->email = $email;
        $this->hashedPassword = $hashedPassword;
    }
}
