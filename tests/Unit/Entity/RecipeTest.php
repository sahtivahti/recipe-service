<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Entity;

use App\Entity\Hop;
use App\Entity\Recipe;
use DateTime;
use PHPUnit\Framework\TestCase;

class RecipeTest extends TestCase
{
    public function testThatConstructorSetsRightProperties(): void
    {
        $recipe = new Recipe();
        $now = (new DateTime())->format('U');

        static::assertSame($now, $recipe->getCreatedAt()->format('U'));
        static::assertSame($now, $recipe->getUpdatedAt()->format('U'));
    }

    public function testThatAddHopAppendsNewHopToRecipe(): void
    {
        $recipe = new Recipe();
        $hop = new Hop();

        $recipe->addHop($hop);

        static::assertSame($hop, $recipe->getHops()->get(0));
    }

    public function testThatRemoveHopRemovesHopFromRecipe(): void
    {
        $recipe = new Recipe();
        $hop = new Hop();

        $recipe->addHop(new Hop());
        $recipe->addHop($hop);

        $recipe->removeHop($recipe->getHops()->get(0));

        static::assertSame($hop, $recipe->getHops()->get(1));
        static::assertCount(1, $recipe->getHops());
    }
}
