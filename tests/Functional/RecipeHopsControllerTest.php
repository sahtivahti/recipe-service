<?php
declare(strict_types = 1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RecipeHopsControllerTest extends WebTestCase
{
    public function testCreateBaseRecipe(): int
    {
        $client = static::createClient();

        $body = [
            'name' => 'Hop test recipe',
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
    public function testThatHopCanBeAddedToRecipe(int $recipeId): array
    {
        $client = static::createClient();

        $body = [
            'name' => 'Galaxy',
            'quantity' => 20.00,
            'time' => 25
        ];

        $client->request('POST', '/v1/recipe/' . $recipeId . '/hop', $body);

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $hopId = $data['id'];

        static::assertGreaterThan(0, $hopId);
        static::assertSame('Galaxy', $data['name']);
        static::assertSame(20, $data['quantity']);
        static::assertSame(25, $data['time']);

        $client->request('GET', '/v1/recipe/' . $recipeId);

        $response = $client->getResponse();
        static::assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        static::assertCount(1, $data['hops']);

        return [$recipeId, $hopId];
    }

    /**
     * @depends testThatHopCanBeAddedToRecipe
     *
     * @param array $ref
     */
    public function testThatHopCanBeRemovedFromRecipe(array $ref): void
    {
        [$recipeId, $hopId] = $ref;

        $client = static::createClient();

        $client->request('DELETE', '/v1/recipe/' . $recipeId . '/hop/' . $hopId);

        $response = $client->getResponse();

        static::assertSame(200, $response->getStatusCode());

        $client->request('GET', '/v1/recipe/' . $recipeId);

        $response = $client->getResponse();
        static::assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        static::assertCount(0, $data['hops']);
    }

    public function testThatAddHopReturns404IfRecipeNotFound(): void
    {
        $client = static::createClient();

        $body = [
            'name' => 'Galaxy',
            'quantity' => 20.00
        ];

        $client->request('POST', '/v1/recipe/99999/hop', $body);

        $response = $client->getResponse();

        static::assertSame(404, $response->getStatusCode());
    }

    public function testThatRemoveHopReturns404IfRecipeNotFound(): void
    {
        $client = static::createClient();

        $client->request('DELETE', '/v1/recipe/99999/hop/60');

        $response = $client->getResponse();

        static::assertSame(404, $response->getStatusCode());
    }

    /**
     * @depends testCreateBaseRecipe
     *
     * @param int $recipeId
     */
    public function testThatRemoveHopReturns404IfHopIsNotFound(int $recipeId): void
    {
        $client = static::createClient();

        $client->request('DELETE', '/v1/recipe/' . $recipeId . '/hop/60');

        $response = $client->getResponse();

        static::assertSame(404, $response->getStatusCode());
    }

    /**
     * @depends testCreateBaseRecipe
     *
     * @param int $recipeId
     */
    public function testThatAddHopWithInvalidBodyReturns400(int $recipeId): void
    {
        $client = static::createClient();

        $body = ['name' => 'Galaxy'];

        $client->request('POST', '/v1/recipe/' . $recipeId . '/hop', $body);

        $response = $client->getResponse();

        static::assertSame(400, $response->getStatusCode());
    }
}
