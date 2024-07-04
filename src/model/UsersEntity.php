<?php

namespace src\model;

use DateTime;

/**
 * Class UsersEntity
 *
 * Represents a record in the "users" table.
 *
 * @property-read int $id The unique identifier of the user.
 * @property-read string $email The email address of the user.
 * @property-read string $hashedPassword The hashed password of the user.
 * @property-read string|null $verificationToken The verification token (if any).
 * @property-read DateTime|null $tokenExpiry The verification token expiry date and time (if any).
 * @property-read bool $verified The verification status (0: not verified, 1: verified). Default: 0
 */
class UsersEntity
{
    public function __construct(
        public ?int $id = null,
        public ?string $email = null,
        public ?string $hashedPassword = null,
        public ?string $verificationToken = null,
        public ?bool $verified = false,
        public ?DateTime $tokenExpiry = null
    ) {}

    public function fieldMap(): array
    {
        return [
            'id' => SQLITE3_INTEGER,
            'email' => SQLITE3_TEXT,
            'hashedPassword' => SQLITE3_TEXT,
            'verificationToken' => SQLITE3_TEXT,
            'verified' => SQLITE3_INTEGER,
            'tokenExpiry' => SQLITE3_TEXT,
        ];
    }

    /**
     * Sets the user's unique identifier.
     *
     * @param int $id The user's ID.
     * @return $this To allow method chaining.
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Sets the user's email address.
     *
     * @param string $email The user's email address.
     * @return $this To allow method chaining.
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Hashes the given password using a strong algorithm (bcrypt) and sets it.
     *
     * @param string $password The plaintext password to hash.
     * @return $this To allow method chaining.
     */
    public function setHashedPassword(string $password): self
    {
        $this->hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }

    /**
     * Sets the verification token used for email verification or password reset.
     *
     * @param string $verificationToken The unique verification token.
     * @return $this To allow method chaining.
     */
    public function setVerificationToken(string $verificationToken): self
    {
        $this->verificationToken = $verificationToken;
        return $this;
    }

    /**
     * Sets the date and time when the verification token expires.
     *
     * @param DateTime $tokenExpiry The expiry time for the verification token.
     * @return $this To allow method chaining.
     */
    public function setTokenExpiry(DateTime $tokenExpiry): self
    {
        $this->tokenExpiry = $tokenExpiry;
        return $this;
    }

    /**
     * Sets the user's verification status (whether the email address is verified).
     *
     * @param bool $verified True if verified, false otherwise.
     * @return $this To allow method chaining.
     */
    public function setVerified(bool $verified): self
    {
        $this->verified = $verified;
        return $this;
    }

    /**
     * Prepares the user for a new verification process.
     * 
     * This generates a fresh verification token, sets a short expiry time, and marks the user as unverified.
     *
     * @return $this To allow method chaining.
     */
    public function prepareVerification(): void
    {
        $this
            ->setVerificationToken(bin2hex(random_bytes(16)))
            ->setTokenExpiry(new DateTime('+10 minutes'))
            ->setVerified(false);
    }

    /**
     * Checks if the user has been created in the database.
     *
     * @return bool True if the user has an ID, false otherwise.
     */
    public function isCreated(): bool
    {
        return isset($this->id);
    }

     /**
     * Checks if the email and password are set.
     * @return bool
     */
    public function isValid(): bool
    {
        return !empty($this->email) && !empty($this->hashedPassword);
    }

    /**
     * Checks if the verification token has expired.
     *
     * @return bool True if the token is expired, false otherwise.
     */
    public function isTokenExpired(): bool
    {
        return $this->tokenExpiry !== null && $this->tokenExpiry < new DateTime();
    }

    /**
     * Checks if the user can be verified.
     *
     * @return bool True if the user is not already verified and has a valid verification token.
     */
    public function isReadyForVerification(): bool
    {
        return !$this->verified && $this->verificationToken && !$this->isTokenExpired();
    }
}
