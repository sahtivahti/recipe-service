<?php
declare(strict_types = 1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RecipeFermentablesControllerTest extends WebTestCase
{
    public function testCreateBaseRecipe(): int
    {
        $client = static::createClient();

        $body = [
            'name' => 'Fermentables test recipe',
            'author' => 'sahti.vahti@sahtivahti.fi',
            'userId' => 'auth0|foobar'
        ];

        $client->request('POST', '/v1/recipe', $body);

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        static::assertGreaterThan(0, $data['id']);

        return $data['id'];
    }

    /**
     * @depends testCreateBaseRecipe
     *
     * @param int $recipeId
     *
     * @return array
     */
    public function testThatFermentableCanBeAddedToRecipe(int $recipeId): array
    {
        $client = static::createClient();

        $body = [
            'name' => 'Pale Ale',
            'quantity' => 5.0,
            'color' => 7.0
        ];

        $client->request('POST', '/v1/recipe/' . $recipeId . '/fermentable', $body);

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $fermentableId = $data['id'];

        static::assertGreaterThan(0, $fermentableId);
        static::assertSame('Pale Ale', $data['name']);
        static::assertSame(5, $data['quantity']);
        static::assertSame(7, $data['color']);

        $client->request('GET', '/v1/recipe/' . $recipeId);

        $response = $client->getResponse();
        static::assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        static::assertCount(1, $data['fermentables']);

        return [$recipeId, $fermentableId];
    }

    /**
     * @depends testThatFermentableCanBeAddedToRecipe
     *
     * @param array $ref
     */
    public function testThatFermentableCanBeRemovedFromRecipe(array $ref): void
    {
        [$recipeId, $fermentableId] = $ref;

        $client = static::createClient();

        $client->request('DELETE', '/v1/recipe/' . $recipeId . '/fermentable/' . $fermentableId);

        $response = $client->getResponse();

        static::assertSame(200, $response->getStatusCode());

        $client->request('GET', '/v1/recipe/' . $recipeId);

        $response = $client->getResponse();
        static::assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        static::assertCount(0, $data['fermentables']);
    }

    public function testThatAddFermentableReturns404IfRecipeNotFound(): void
    {
        $client = static::createClient();

        $body = [
            'name' => 'Vienna',
            'quantity' => 5.0,
            'color' => 6.0
        ];

        $client->request('POST', '/v1/recipe/99999/fermentable', $body);

        $response = $client->getResponse();

        static::assertSame(404, $response->getStatusCode());
    }

    public function testThatRemoveFermentableReturns404IfRecipeNotFound(): void
    {
        $client = static::createClient();

        $client->request('DELETE', '/v1/recipe/99999/fermentable/60');

        $response = $client->getResponse();

        static::assertSame(404, $response->getStatusCode());
    }

    /**
     * @depends testCreateBaseRecipe
     *
     * @param int $recipeId
     */
    public function testThatRemoveFermentableReturns404IfHopIsNotFound(int $recipeId): void
    {
        $client = static::createClient();

        $client->request('DELETE', '/v1/recipe/' . $recipeId . '/fermentable/60');

        $response = $client->getResponse();

        static::assertSame(404, $response->getStatusCode());
    }

    /**
     * @depends testCreateBaseRecipe
     *
     * @param int $recipeId
     */
    public function testThatAddFermentableWithInvalidBodyReturns400(int $recipeId): void
    {
        $client = static::createClient();

        $body = ['name' => 'Vienna'];

        $client->request('POST', '/v1/recipe/' . $recipeId . '/fermentable', $body);

        $response = $client->getResponse();

        static::assertSame(400, $response->getStatusCode());
    }
}
