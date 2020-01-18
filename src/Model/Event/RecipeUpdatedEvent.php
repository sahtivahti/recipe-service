<?php
namespace App\Model\Event;

use App\Entity\Recipe;
use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;

class RecipeUpdatedEvent
{
    /**
     * @Groups({"Details"})
     */
    private Recipe $item;

    /**
     * @Groups({"Details"})
     */
    private DateTime $createdAt;

    /**
     * @Groups({"Details"})
     */
    private string $type;

    public function __construct(Recipe $recipe)
    {
        $this->item = $recipe;
        $this->createdAt = new DateTime();
        $this->type = 'recipe.updated';
    }

    public function getType(): string
    {
        return $this->type;
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
