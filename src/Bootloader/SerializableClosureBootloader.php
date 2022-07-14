<?php

declare(strict_types=1);

namespace Spiral\SerializableClosure\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Config\ConfiguratorInterface;
use Spiral\SerializableClosure\Config\SerializableClosureConfig;
use Spiral\SerializableClosure\Serializer;
use Spiral\Serializer\Bootloader\SerializerBootloader;
use Spiral\Serializer\SerializerRegistryInterface;

final class SerializableClosureBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        SerializerBootloader::class,
    ];

    public function init(ConfiguratorInterface $configs): void
    {
        $configs->setDefaults(SerializableClosureConfig::CONFIG, [
            'secret' => null,
        ]);
    }

    public function boot(SerializerRegistryInterface $registry, SerializableClosureConfig $config): void
    {
        $this->configureSerializer($registry, $config);
    }

    private function configureSerializer(SerializerRegistryInterface $registry, SerializableClosureConfig $config): void
    {
        $registry->register('closure', new Serializer($config->getSecretKey()));
    }
}
