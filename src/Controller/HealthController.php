<?php
declare(strict_types = 1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HealthController
 *
 * @Route(path="/health")
 *
 * @package App\Controller
 * @author atta, Atte Tarvainen <atte.tarvainen@protacon.com>
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
