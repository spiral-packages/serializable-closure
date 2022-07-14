<?php

declare(strict_types=1);

namespace Spiral\SerializableClosure\Tests\App;

class ParentClass
{
    private array $foobar = ['test'];

    public function getFoobar(): array
    {
        return $this->foobar;
    }
}
