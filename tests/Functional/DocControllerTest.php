<?php
declare(strict_types = 1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;

class DocControllerTest extends WebTestCase
{
    public function testThatSwaggerUiPageIsRendered(): void
    {
        $client = static::createClient();

        $client->request('GET', '/doc');
        $response = $client->getResponse();

        static::assertSame(Response::HTTP_OK, $response->getStatusCode());
        static::assertStringEqualsFile(__DIR__ . '/../../templates/docs.html', $response->getContent());
    }

    public function testThatSwaggerJsonReturnsSwaggerYamlAsJson(): void
    {
        $client = static::createClient();

        $client->request('GET', '/doc/swagger.json');

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertSame(
            json_encode(Yaml::parseFile(__DIR__ . '/../../swagger.yaml'), JSON_THROW_ON_ERROR, 512),
            $client->getResponse()->getContent()
        );
    }
}
