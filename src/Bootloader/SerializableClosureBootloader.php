<?php

declare(strict_types=1);

namespace Spiral\SerializableClosure\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\SerializableClosure\Serializer;
use Spiral\Serializer\Bootloader\SerializerBootloader;
use Spiral\Serializer\SerializerRegistryInterface;

final class SerializableClosureBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        SerializerBootloader::class,
    ];

    public function boot(SerializerRegistryInterface $registry): void
    {
        $this->configureSerializer($registry);
    }

    private function configureSerializer(SerializerRegistryInterface $registry): void
    {
        $registry->register('closure', new Serializer());
    }
}
