<?php

declare(strict_types=1);

namespace Spiral\SerializableClosure\Tests\Feature\Bootloader;

use Spiral\SerializableClosure\Config\SerializableClosureConfig;
use Spiral\SerializableClosure\Serializer;
use Spiral\Serializer\SerializerManager;
use Spiral\SerializableClosure\Tests\Feature\TestCase;

final class SerializableClosureBootloaderTest extends TestCase
{
    public function testDefaultConfigIsLoaded(): void
    {
        $config = $this->getConfig(SerializableClosureConfig::CONFIG);

        $this->assertNull($config['secret']);
    }

    public function testSerializerIsConfigured(): void
    {
        $manager = $this->getContainer()->get(SerializerManager::class);

        $this->assertInstanceOf(Serializer::class, $manager->getSerializer('closure'));
    }
}
