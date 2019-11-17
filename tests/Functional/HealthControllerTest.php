<?php
declare(strict_types = 1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HealthControllerTest extends WebTestCase
{
    public function testThatHealthControllerReturnsOk(): void
    {
        $client = static::createClient();

        $client->request('GET', '/health/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame('OK', $client->getResponse()->getContent());
    }
}
