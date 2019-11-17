<?php
declare(strict_types = 1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @return JsonResponse
     */
    public function actionHealth(): JsonResponse
    {
        return new JsonResponse('OK');
    }
}
