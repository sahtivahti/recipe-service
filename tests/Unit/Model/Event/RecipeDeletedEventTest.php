<?php
namespace App\Tests\Unit\Model\Event;

use App\Entity\Recipe;
use App\Model\Event\RecipeDeletedEvent;
use DateTime;
use PHPUnit\Framework\TestCase;

class RecipeDeletedEventTest extends TestCase
{
    public function testThatConstructorSetsProperties(): void
    {
        $now = new DateTime();
        $recipe = new Recipe();

        $event = new RecipeDeletedEvent($recipe);

        static::assertSame($recipe, $event->getItem());
        static::assertSame($now->getTimestamp(), $recipe->getCreatedAt()->getTimestamp());
        static::assertSame('recipe.deleted', $event->getType());
    }
}
