<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Log\Factory;

use Kiboko\Contract\Configurator;
use Kiboko\Plugin\Log\Builder;
use Kiboko\Plugin\Log\Configuration;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception as Symfony;
use Symfony\Component\Config\Definition\Processor;

final readonly class StreamFactory implements Configurator\FactoryInterface
{
    private Processor $processor;
    private ConfigurationInterface $configuration;

    public function __construct()
    {
        $this->processor = new Processor();
        $this->configuration = new Configuration\StreamConfiguration();
    }

    public function configuration(): ConfigurationInterface
    {
        return $this->configuration;
    }

    /**
     * @throws Configurator\ConfigurationExceptionInterface
     */
    public function normalize(array $config): array
    {
        try {
            return $this->processor->processConfiguration($this->configuration, $config);
        } catch (Symfony\InvalidTypeException|Symfony\InvalidConfigurationException $exception) {
            throw new Configurator\InvalidConfigurationException($exception->getMessage(), 0, $exception);
        }
    }

    public function validate(array $config): bool
    {
        try {
            $this->processor->processConfiguration($this->configuration, $config);

            return true;
        } catch (\Exception) {
        }

        return false;
    }

    public function compile(array $config): Repository\StreamRepository
    {
        $builder = new Builder\Monolog\StreamBuilder($config['path']);

        if (\array_key_exists('level', $config)) {
            $builder->withLevel($config['level']);
        }

        if (\array_key_exists('file_permissions', $config)) {
            $builder->withFilePermissions($config['file_permissions']);
        }

        if (\array_key_exists('use_locking', $config)) {
            $builder->withLocking($config['use_locking']);
        }

        return new Repository\StreamRepository($builder);
    }
}
