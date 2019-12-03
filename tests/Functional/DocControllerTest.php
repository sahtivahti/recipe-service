<?php
declare(strict_types = 1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Yaml\Yaml;

class DocControllerTest extends WebTestCase
{
    public function testThatSwaggerJsonReturnsSwaggerYamlAsJson(): void
    {
        $client = static::createClient();

        $client->request('GET', '/doc/swagger.json');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $this->assertSame(
            json_encode(Yaml::parseFile(__DIR__ . '/../../swagger.yaml'), JSON_THROW_ON_ERROR, 512),
            $client->getResponse()->getContent()
        );
    }
}
