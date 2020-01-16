<?php
namespace App\Model\Event;

use App\Entity\Recipe;
use DateTime;

class RecipeUpdatedEvent
{
    private Recipe $recipe;

    private DateTime $createdAt;

    public function __construct(Recipe $recipe)
    {
        $this->recipe = $recipe;
        $this->createdAt = new DateTime();
    }

    /**
     * @return Recipe
     */
    public function getRecipe(): Recipe
    {
        return $this->recipe;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}
