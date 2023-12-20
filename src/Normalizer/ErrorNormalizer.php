<?php
namespace App\Normalizer;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @method array getSupportedTypes(?string $format)
 */
class ErrorNormalizer implements NormalizerInterface
{
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        return [
            'message' => $context['debug'] ? $object->getMessage() : 'An error occured',
            'status' => $object->getStatusCode(),
            'trace' => $context['debug'] ? $object->getTrace() : [],
        ];
    }
    
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof FlattenException;
    }
    
    public function __call (string $name, array $arguments)
    {
        // TODO: Implement @method array getSupportedTypes(?string $format)
    }
}