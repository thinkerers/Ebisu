<?php

namespace src\lib;

class Fishing
{
    /**
     * Rarity distribution percentages for catching fish.
     *
     * @var array
     */
    public array $rarities;

    /**
     * Available variants for each fish rarity.
     *
     * @var array
     */
    public array $variants;

    /**
     * Constructor to initialize rarity and variant distributions.
     */
    public function __construct(
        array $rarities = [
            'normal' => 70,
            'rare' => 20,
            'ultra-rare' => 9,
            'legendaire' => 1
        ],
        array $variants = [
            'a' => 1,
            'b' => 1,
            'c' => 1,
            'd' => 1,
            'e' => 1,
            'f' => 1,
            'g' => 1,
            'h' => 1,
            'i' => 1,
        ]
    ) {
        $this->rarities = $rarities;
        $this->variants = $variants;
    }

    /**
     * Simulates the act of catching a fish.
     *
     * @return object The caught fish with its rarity and variant.
     */
    public function catch(): object
    {
        $fish = $this->getRandomRarity();
        return (object)$fish;
    }

    /**
     * Determines a random rarity and variant based on weighted probabilities.
     *
     * @return object An object containing the selected rarity, variant, and fishId.
     */
    public function getRandomRarity(): object
    {
        $rarity = $this->weightedRandomChoice($this->rarities);
        $variant = $this->weightedRandomChoice($this->variants);

        $rarityIndex = array_search($rarity, array_keys($this->rarities));
        $variantIndex = array_search($variant, array_keys($this->variants));
        $fishId = $rarityIndex * 10 + $variantIndex;

        return (object)['rarity' => $rarity, 'variant' => $variant, 'fishId' => $fishId];
    }

    /**
     * Selects a random choice from an array with weighted probabilities.
     *
     * @param array $choices An associative array where keys are choices and values are weights.
     * @return string The selected choice.
     * @throws \Exception If unable to make a choice.
     */
    public function weightedRandomChoice(array $choices): string
    {
        $totalWeight = array_sum(array_values($choices));
        $selection = random_int(1, $totalWeight);
        $count = 0;
        
        foreach ($choices as $choice => $weight) {
            $count += $weight;
            if ($count >= $selection) {
                return $choice;
            }
        }

        throw new \Exception('Unable to make a choice!');
    }

    /**
     * Returns an array of discovered fish IDs (example implementation).
     *
     * @return array
     */
    public function discovered($userId): array
    {
        return (new \src\model\Account())->getDiscoveredFish($userId);
    }


    public function save($fish,$userId)
    {
        //store fish data in the database for the given user
        (new \src\model\Account())->storeFish($fish,$userId);
    }
}
