<?php
declare(strict_types = 1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/health")
 */
class HealthController
{
    /**
     * @Route(path="/")
     *
     * @return Response
     */
    public function actionHealth(): Response
    {
        return new Response('OK');
    }
}
