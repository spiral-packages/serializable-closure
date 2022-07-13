<?php

declare(strict_types=1);

namespace Spiral\SerializableClosure\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;

class SerializableClosureBootloader extends Bootloader
{
    protected const BINDINGS = [];
    protected const SINGLETONS = [];
    protected const DEPENDENCIES = [];

    public function init(): void
    {
    }

    public function boot(): void
    {
    }
}
