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
        $content = <<<EOF
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <title>API Documentation</title>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@3.12.1/swagger-ui.css">
    <style>* { margin: 0; }</style>
</head>
<body>
<div id="swagger-ui"></div>

<script src="https://unpkg.com/swagger-ui-dist@3.12.1/swagger-ui-standalone-preset.js"></script>
<script src="https://unpkg.com/swagger-ui-dist@3.12.1/swagger-ui-bundle.js"></script>

<script>
    window.onload = function() {
        SwaggerUIBundle({
            url: '/doc/swagger.json',
            dom_id: '#swagger-ui',
            deepLinking: true,
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],
            plugins: [
                SwaggerUIBundle.plugins.DownloadUrl
            ],
            layout: 'StandaloneLayout',
        })
    }
</script>
</body>
</html>
EOF;

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
