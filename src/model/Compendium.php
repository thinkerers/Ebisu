<?php

namespace src\model;

/**
 * Class Compendium
 *
 * Represents a record in the "compendium" table.
 *
 * @property int $id The unique identifier of the compendium entry.
 * @property int $rank The rank of the compendium entry.
 * @property string $name The name of the compendium entry.
 * @property string $description The description of the compendium entry.
 * @property string $img The image URL of the compendium entry.
 */
class Compendium
{
    public $id;
    public $rank;
    public $name;
    public $description;
    public $img;

    /**
     * Constructor to initialize properties.
     *
     * @param int $id
     * @param int $rank
     * @param string $name
     * @param string $description
     * @param string $img
     */
    public function __construct(int $id, int $rank, string $name, string $description, string $img)
    {
        $this->id = $id;
        $this->rank = $rank;
        $this->name = $name;
        $this->description = $description;
        $this->img = $img;
    }
}
