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
    /**
     * Constructor to initialize properties.
     *
     * @param int $id
     * @param int $rank
     * @param string $name
     * @param string $description
     * @param string $img
     */
    public function __construct(
        public ?int $id = null, 
        public ?int $rank = null, 
        public ?string $name = null, 
        public ?string $description = null,  
        public ?string $img = null
        )
    {}
}
