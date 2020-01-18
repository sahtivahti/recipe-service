<?php
namespace App\Tests\Unit\Model\Event;

use App\Entity\Recipe;
use App\Model\Event\RecipeCreatedEvent;
use DateTime;
use PHPUnit\Framework\TestCase;

class RecipeCreatedEventTest extends TestCase
{
    public function testThatConstructorSetsProperties(): void
    {
        $now = new DateTime();
        $recipe = new Recipe();

        $event = new RecipeCreatedEvent($recipe);

        static::assertSame($recipe, $event->getItem());
        static::assertSame($now->getTimestamp(), $event->getCreatedAt()->getTimestamp());
        static::assertSame('recipe.created', $event->getType());
    }
}
