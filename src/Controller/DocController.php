<?php
declare(strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;

/**
 * @Route(path="/doc")
 */
class DocController extends AbstractController
{
    /**
     * @Route(path="")
     *
     * @return Response
     */
    public function actionSwaggerUi(): Response
    {
        $content = file_get_contents(__DIR__ . '/../../templates/docs.html');

        return new Response($content);
    }

    /**
     * @Route(path="/swagger.json")
     *
     * @return JsonResponse
     */
    public function actionSwaggerJson(): JsonResponse
    {
        $contents = Yaml::parseFile(__DIR__ . '/../../swagger.yaml');

        return $this->json($contents);
    }
}
