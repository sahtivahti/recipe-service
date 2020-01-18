<?php
namespace App\Model\Event;

use App\Entity\Recipe;
use DateTime;

class RecipeDeletedEvent
{
    private Recipe $item;

    private DateTime $createdAt;

    public function __construct(Recipe $recipe)
    {
        $this->item = $recipe;
        $this->createdAt = new DateTime();
    }

    public function getType(): string
    {
        return 'recipe.deleted';
    }

    public function getItem(): Recipe
    {
        return $this->item;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}
