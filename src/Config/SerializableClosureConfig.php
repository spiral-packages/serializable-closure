<?php

declare(strict_types=1);

namespace Spiral\SerializableClosure\Config;

use Spiral\Core\InjectableConfig;

final class SerializableClosureConfig extends InjectableConfig
{
    public const CONFIG = 'serializable-closure';

    protected array $config = [
        'secret' => null,
    ];

    public function getSecretKey(): ?string
    {
        return $this->config['secret'] ?? null;
    }
}
