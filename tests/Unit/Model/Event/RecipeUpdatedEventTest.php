<?php
namespace App\Tests\Unit\Model\Event;

use App\Entity\Recipe;
use App\Model\Event\RecipeUpdatedEvent;
use DateTime;
use PHPUnit\Framework\TestCase;

class RecipeUpdatedEventTest extends TestCase
{
    public function testThatConstructorSetsProperties(): void
    {
        $now = new DateTime();
        $recipe = new Recipe();

        $event = new RecipeUpdatedEvent($recipe);

        static::assertSame($recipe, $event->getItem());
        static::assertSame($now->getTimestamp(), $event->getCreatedAt()->getTimestamp());
        static::assertSame('recipe.updated', $event->getType());
    }
}
