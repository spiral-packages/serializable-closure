<?php

declare(strict_types=1);

namespace Spiral\SerializableClosure;

use Laravel\SerializableClosure\SerializableClosure;
use Laravel\SerializableClosure\Support\ClosureScope;
use Spiral\Serializer\SerializerInterface;

class Serializer implements SerializerInterface
{
    public function __construct(?string $secretKey = null)
    {
        if ($secretKey !== null) {
            SerializableClosure::setSecretKey($secretKey);
        }
    }

    public function serialize(mixed $payload): string|\Stringable
    {
        if ($payload instanceof \Closure) {
            return \serialize(new SerializableClosure($payload));
        }

        Native::wrapClosures($payload, new ClosureScope());

        return \serialize($payload);
    }

    public function unserialize(\Stringable|string $payload, object|string|null $type = null): mixed
    {
        $data = \unserialize((string) $payload);

        if ($data instanceof SerializableClosure) {
            return $data->getClosure();
        }

        Native::unwrapClosures($data, new ClosureScope());

        return $data;
    }
}
