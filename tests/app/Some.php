<?php

declare(strict_types=1);

namespace Spiral\SerializableClosure\Tests\App;

class Some
{
    private mixed $f;

    public function __construct(mixed $f)
    {
        $this->f = $f;
    }

    public function getF(): mixed
    {
        return $this->f;
    }

    public function test(mixed $value): mixed
    {
        $f = $this->f;
        return $f($value);
    }
}
