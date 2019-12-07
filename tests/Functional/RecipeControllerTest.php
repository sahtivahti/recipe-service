<?php
declare(strict_types = 1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RecipeControllerTest extends WebTestCase
{
    public function testThatNewRecipeCanBeCreated(): void
    {
        $client = static::createClient();

        $body = [
            'name' => 'My another beer recipe',
            'author' => 'panomestari@sahtivahti.fi'
        ];

        $client->request('POST', '/v1/recipe', $body);

        $response = $client->getResponse();

        static::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        static::assertJson($response->getContent());
    }
}
