# Laravel Serializable Closure bridge for Spiral Framework

[![PHP](https://img.shields.io/packagist/php-v/spiral-packages/serializable-closure.svg?style=flat-square)](https://packagist.org/packages/spiral-packages/serializable-closure)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/spiral-packages/serializable-closure.svg?style=flat-square)](https://packagist.org/packages/spiral-packages/serializable-closure)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spiral-packages/serializable-closure/run-tests?label=tests&style=flat-square)](https://github.com/spiral-packages/serializable-closure/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spiral-packages/serializable-closure.svg?style=flat-square)](https://packagist.org/packages/spiral-packages/serializable-closure)

## Requirements

Make sure that your server is configured with following PHP version and extensions:

- PHP 8.1+
- Spiral framework 3.0+

## Installation

You can install the package via composer:

```bash
composer require spiral-packages/serializable-closure
```

After package install you need to register bootloader from the package.

```php
protected const LOAD = [
    // ...
    \Spiral\SerializableClosure\Bootloader\SerializableClosureBootloader::class,
];
```

> **Note**
> If you are using [`spiral-packages/discoverer`](https://github.com/spiral-packages/discoverer),
> you don't need to register bootloader by yourself.

## Configuration
The package configuration provides the ability to set up a secret key that the 
`Laravel\SerializableClosure\SerializableClosure` class will use for signing.

Create a file `app/config/serializable-closure.php`.
Add the `secret` parameters. 
For example:
```php
<?php

declare(strict_types=1);

return [
    'secret' => 'secret-key',
];
```

## Usage
Using with `Spiral\Serializer\SerializerManager`. 
For example:
```php
use Spiral\Serializer\SerializerManager;

$manager = $this->container->get(SerializerManager::class); 
$serializer = $manager->getSerializer('closure');

$result = $serializer->serialize($payload);
$result = $serializer->unserialize($payload);
```
Also, you can set closure to be the default serializer in the `serializer` config file and use 
`Spiral\Serializer\SerializerInterface`. 
Example:
```php
// config serializer.php

return [
    'default' => 'closure',
];

// usage
use Spiral\Serializer\SerializerInterface;

$serializer = $this->container->get(SerializerInterface::class); 

$result = $serializer->serialize($payload);
$result = $serializer->unserialize($payload);
```


## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
