<?php


namespace App\Infrastructure\Api;

use OpenApi\Annotations\Info;
use OpenApi\Annotations\SecurityScheme;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Component\Routing\Annotation\Route;
use function OpenApi\scan;

/**
 * @Info(title="doskaz api", version="1")
 * @SecurityScheme(securityScheme="clientAuth", type="http", scheme="bearer", bearerFormat="token")
 */
class DocumentationController extends AbstractController
{
    private string $srcDir;

    public function __construct(string $srcDir)
    {
        $this->srcDir = $srcDir;
    }

    /**
     * @Route(path="/api/swagger.json", name="api.swagger.json")
     */
    public function swaggerJson()
    {
        $openapi = scan($this->srcDir);
        return JsonResponse::fromJsonString($openapi->toJson());
    }

    /**
     * @Route(path="/api/docs")
     * @Template(template="api_docs.html.twig")
     * @param Profiler|null $profiler
     * @return array
     */
    public function docs(?Profiler $profiler)
    {
        if ($profiler) {
            $profiler->disable();
        }
        return [
            'spec' => scan($this->srcDir)->toJson()
        ];
    }
}
