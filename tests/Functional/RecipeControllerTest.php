<?php
declare(strict_types = 1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use function json_decode;

class RecipeControllerTest extends WebTestCase
{
    public function testThatNewRecipeCanBeCreated(): int
    {
        $client = static::createClient();

        $body = [
            'name' => 'My another beer recipe',
            'author' => 'panomestari@sahtivahti.fi'
        ];

        $client->request('POST', '/v1/recipe', $body);

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        static::assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        static::assertSame('My another beer recipe', $data['name']);
        static::assertSame('panomestari@sahtivahti.fi', $data['author']);

        return $data['id'];
    }

    /**
     * @depends testThatNewRecipeCanBeCreated
     *
     * @param int $recipeId
     */
    public function testThatRecipeCanBeFetched(int $recipeId): void
    {
        $client = static::createClient();

        $client->request('GET', '/v1/recipe/' . $recipeId);
        $response = $client->getResponse();

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        static::assertSame(Response::HTTP_OK, $response->getStatusCode());
        static::assertSame($recipeId, $data['id']);
        static::assertSame('My another beer recipe', $data['name']);
        static::assertSame('panomestari@sahtivahti.fi', $data['author']);
    }

    /**
     * @depends testThatNewRecipeCanBeCreated
     *
     * @param int $recipeId
     */
    public function testThatRecipeCanBeUpdated(int $recipeId): void
    {
        $client = static::createClient();

        $body = [
            'name' => 'My updated beer recipe',
            'author' => 'owner-changed@sahtivahti.fi'
        ];

        $client->request('PUT', '/v1/recipe/' . $recipeId, $body);
        $response = $client->getResponse();

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        static::assertSame(Response::HTTP_OK, $response->getStatusCode());
        static::assertSame('My updated beer recipe', $data['name']);
        static::assertSame('owner-changed@sahtivahti.fi', $data['author']);
    }

    /**
     * @depends testThatNewRecipeCanBeCreated
     *
     * @param int $recipeId
     */
    public function testThatCreatedRecipeShowsInListing(int $recipeId): void
    {
        $client = static::createClient();

        $client->request('GET', '/v1/recipe');
        $response = $client->getResponse();

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        static::assertSame(Response::HTTP_OK, $response->getStatusCode());
        static::assertCount(1, array_filter($data, fn(array $x): bool => $x['id'] === $recipeId));
    }

    /**
     * @depends testThatNewRecipeCanBeCreated
     *
     * @param int $recipeId
     *
     * @return int
     */
    public function testThatCreatedRecipeCanBeRemoved(int $recipeId): int
    {
        $client = static::createClient();

        $client->request('DELETE', '/v1/recipe/' . $recipeId);
        $response = $client->getResponse();

        static::assertSame(Response::HTTP_OK, $response->getStatusCode());

        return $recipeId;
    }

    /**
     * @depends testThatCreatedRecipeCanBeRemoved
     *
     * @param int $recipeId
     */
    public function testThatRemovedEventDoesNotShowInListing(int $recipeId): void
    {
        $client = static::createClient();

        $client->request('GET', '/v1/recipe');
        $response = $client->getResponse();

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        static::assertSame(Response::HTTP_OK, $response->getStatusCode());
        static::assertCount(0, array_filter($data, fn(array $x): bool => $x['id'] === $recipeId));
    }
}
