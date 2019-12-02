<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Entity;

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
}
