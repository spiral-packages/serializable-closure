<?php

namespace VendorName\Skeleton\Tests;

use Spiral\Boot\Bootloader\ConfigurationBootloader;
use Spiral\SerializableClosure\Bootloader\SerializableClosureBootloader;

class TestCase extends \Spiral\Testing\TestCase
{
    public function rootDirectory(): string
    {
        return __DIR__.'/../';
    }

    public function defineBootloaders(): array
    {
        return [
            ConfigurationBootloader::class,
            SerializableClosureBootloader::class,
        ];
    }
}
