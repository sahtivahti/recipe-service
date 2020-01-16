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
            'author' => 'panomestari@sahtivahti.fi',
            'userId' => 'auth0|foobar',
            'style' => 'IPA',
            'batchSize' => 15.6
        ];

        $client->request('POST', '/v1/recipe', $body);

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        static::assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        static::assertSame('My another beer recipe', $data['name']);
        static::assertSame('panomestari@sahtivahti.fi', $data['author']);
        static::assertSame('auth0|foobar', $data['userId']);
        static::assertSame('IPA', $data['style']);
        static::assertSame(15.6, $data['batchSize']);

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
        static::assertSame('auth0|foobar', $data['userId']);
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
            'author' => 'owner-changed@sahtivahti.fi',
            'style' => 'Traditional beer',
            'batchSize' => 5.6
        ];

        $client->request('PUT', '/v1/recipe/' . $recipeId, $body);
        $response = $client->getResponse();

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        static::assertSame(Response::HTTP_OK, $response->getStatusCode());
        static::assertSame('My updated beer recipe', $data['name']);
        static::assertSame('owner-changed@sahtivahti.fi', $data['author']);
        static::assertSame('auth0|foobar', $data['userId']);
        static::assertSame('Traditional beer', $data['style']);
        static::assertSame(5.6, $data['batchSize']);
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
     * @depends  testThatNewRecipeCanBeCreated
     *
     * @param int $recipeId
     */
    public function testThatSearchingWithUserIdInQueryParametersFiltersResults(int $recipeId): void
    {
        $client = static::createClient();

        $client->request('GET', '/v1/recipe?userId=userWithNoRecipes');
        $response = $client->getResponse();

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        static::assertSame(Response::HTTP_OK, $response->getStatusCode());
        static::assertCount(0, $data);
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

    public function testThatGetRecipeReturns404IfNotFound(): void
    {
        $client = static::createClient();

        $client->request('GET', '/v1/recipe/999999');

        static::assertSame(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testThatUpdateRecipeReturns404IfNotFound(): void
    {
        $client = static::createClient();

        $body = [
            'name' => 'My updated beer recipe',
            'author' => 'owner-changed@sahtivahti.fi',
            'userId' => 'auth0|foobar'
        ];

        $client->request('PUT', '/v1/recipe/999999', $body);

        static::assertSame(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testThatDeleteRecipeReturns404IfNotFound(): void
    {
        $client = static::createClient();

        $client->request('DELETE', '/v1/recipe/999999');

        static::assertSame(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testThatCreateRecipeReturns400WithInvalidBody(): void
    {
        $client = static::createClient();

        $client->request('POST', '/v1/recipe');

        static::assertSame(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    /**
     * @depends testThatNewRecipeCanBeCreated
     *
     * @param int $recipeId
     */
    public function testThatUpdateRecipeReturns400WithInvalidBody(int $recipeId): void
    {
        $client = static::createClient();

        $client->request('PUT', '/v1/recipe/' . $recipeId);

        static::assertSame(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }
}
