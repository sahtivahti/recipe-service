<?php
declare(strict_types = 1);

namespace App\Tests\Unit\EventSubscriber;

use App\EventSubscriber\BodySubscriber;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class BodySubscriberTest extends TestCase
{
    public function testThatJsonBodyIsReplaced(): void
    {
        $subscriber = new BodySubscriber();

        $request = new Request([], [], [], [], [], [], '{ "id": "foo" }');

        /** @var RequestEvent|MockObject $event */
        $event = $this->createMock(RequestEvent::class);
        $event->method('getRequest')->willReturn($request);

        $subscriber->onKernelRequest($event);

        static::assertEquals(['id' => 'foo'], $request->request->all());
    }
}
