<?php
declare(strict_types = 1);

namespace App\Helpers;

use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class RequestObjectResolver implements ArgumentValueResolverInterface
{
    private DenormalizerInterface $deNormalizer;

    public function __construct(DenormalizerInterface $deNormalizer)
    {
        $this->deNormalizer = $deNormalizer;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        // Limit automatic resolving only for own classes
        return strpos($argument->getType(), 'App\\') === 0 && class_exists($argument->getType());
    }

    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        yield $this->deNormalizer->denormalize($request->request->all(), $argument->getType());
    }
}
