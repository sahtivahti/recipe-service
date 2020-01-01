<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Model;

use App\Model\RecipeSearchFilters;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RecipeSearchFiltersTest extends TestCase
{
    /**
     * @param RecipeSearchFilters $filters
     * @param string $filterQuery
     * @param array $filterParameter
     *
     * @dataProvider dataProviderFilters
     */
    public function testThatFiltersAreAppliedProperly(
        RecipeSearchFilters $filters,
        string $filterQuery,
        array $filterParameter
    ): void {
        /** @var QueryBuilder|MockObject $qbMock */
        $qbMock = $this->createMock(QueryBuilder::class);
        $qbMock->method(static::anything())->willReturn($qbMock);

        $qbMock->expects(static::once())->method('andWhere')->with($filterQuery);
        $qbMock->expects(static::once())->method('setParameter')->with(...$filterParameter);

        $filters->applyTo($qbMock);
    }

    public function testThatFiltersAddsPagination(): void
    {
        /** @var QueryBuilder|MockObject $qbMock */
        $qbMock = $this->createMock(QueryBuilder::class);
        $qbMock->method(static::anything())->willReturn($qbMock);

        $qbMock->expects(static::once())->method('setFirstResult')->with(20);

        $filters = (new RecipeSearchFilters())->setPage(2);

        $filters->applyTo($qbMock);
    }

    public function testSettersAndGetters(): void
    {
        $filters = (new RecipeSearchFilters())
            ->setUserId('userId')
            ->setName('name')
            ->setAuthor('author')
            ->setPage(2);

        static::assertSame('userId', $filters->getUserId());
        static::assertSame('name', $filters->getName());
        static::assertSame('author', $filters->getAuthor());
        static::assertSame(2, $filters->getPage());
    }

    public function dataProviderFilters(): array
    {
        return [
            [
                (new RecipeSearchFilters())
                    ->setAuthor('foo@email.com'),
                'r.author = :author',
                [
                    'author',
                    'foo@email.com'
                ]
            ],
            [
            (new RecipeSearchFilters())
                ->setName('recipe'),
                'r.name like :name',
                [
                    'name',
                    '%recipe%'
                ]
            ],
            [
                (new RecipeSearchFilters())
                    ->setUserId('foo|auth0'),
                'r.userId = :userId',
                [
                    'userId',
                    'foo|auth0'
                ]
            ]
        ];
    }
}
