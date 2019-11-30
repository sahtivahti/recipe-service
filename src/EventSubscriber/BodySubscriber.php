<?php
declare(strict_types = 1);

namespace App\EventSubscriber;

use function json_decode;
use LogicException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use function in_array;

class BodySubscriber implements EventSubscriberInterface
{
    /**
     * @return mixed[] The event names to listen to
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 10],
        ];
    }

    /**
     * @param RequestEvent $event
     *
     * @throws LogicException
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (empty($request->getContent())) {
            return;
        }

        if ($this->isJsonRequest($request)) {
            $this->transformJsonBody($request);
        }
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    private function isJsonRequest(Request $request): bool
    {
        return in_array($request->getContentType(), [null, 'json', 'txt'], true);
    }

    /**
     * @param Request $request
     *
     * @throws LogicException
     */
    private function transformJsonBody(Request $request): void
    {
        /** @var string $content */
        $content = $request->getContent();

        /** @var array $data */
        $data = json_decode($content, true);

        $request->request->replace($data ?: []);
    }
}
