<?php


namespace src\model;

use DateTime;

/**
 * Class CompendiumEntity
 *
 * Represents a record in the "compendium" table.
 *
 * @property-read int $id The unique identifier of the compendium entry.
 * @property-read int $rank The rank of the compendium entry.
 * @property-read string $name The name of the compendium entry.
 * @property-read string $description The description of the compendium entry.
 * @property-read string $img The image URL of the compendium entry.
 */
class CompendiumEntity
{
    public function __construct(
        public ?int $id = null, 
        public ?int $rank = null, 
        public ?string $name = null, 
        public ?string $description = null,  
        public ?string $img = null
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
            'rank' => SQLITE3_INTEGER,
            'name' => SQLITE3_TEXT,
            'description' => SQLITE3_TEXT,
            'img' => SQLITE3_TEXT
        ];
    }

    /**
     * Sets the compendium Entity session's unique identifier.
     *
     * @param int $id The compendium Entity session's ID.
     * @return $this To allow method chaining.
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Sets the rank of the compendium Entity.
     *
     * @param int $rank The rank of the compendium Entity.
     * @return $this To allow method chaining.
     */
    public function setRank(int $rank): self
    {
        $this->rank = $rank;
        return $this;
    }

    /**
     * Sets the name of the compendium Entity.
     *
     * @param string $name The name of the compendium Entity.
     * @return $this To allow method chaining.
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Sets the description of the compendium Entity.
     *
     * @param string $description The description of the compendium Entity.
     * @return $this To allow method chaining.
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Sets the image URL of the compendium Entity.
     *
     * @param string $img The image URL of the compendium Entity.
     * @return $this To allow method chaining.
     */
    public function setImg(string $img): self
    {
        $this->img = $img;
        return $this;
    }


    /**
     * Checks if the compendium Entity has been created.
     *
     * @return bool True if the compendium Entity has an ID, false otherwise.
     */
    public function isCreated(): bool
    {
        return isset($this->id);
    }

     /**
     * Checks if the compendium Entity has the required properties.
     * @return bool
     */
    public function isValid(): bool
    {
        return 
           !empty($this->id)
        && !empty($this->rank)
        && !empty($this->name)
        && !empty($this->description)
        && !empty($this->img)
        ;
    }
}
