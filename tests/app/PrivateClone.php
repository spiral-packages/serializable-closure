<?php

declare(strict_types=1);

namespace Spiral\SerializableClosure\Tests\App;

class PrivateClone
{
    private mixed $a = 1;

    private function __clone()
    {
    }

    public function value(): mixed
    {
        return $this->a;
    }

    public function create(): \Closure
    {
        return fn () => $this->a;
    }
}
