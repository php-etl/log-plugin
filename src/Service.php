<?php declare(strict_types=1);

namespace Kiboko\Plugin\Log;

use Kiboko\Contract\Configurator;
use Kiboko\Plugin\Log\Builder\LogstashFormatterBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception as Symfony;
use Symfony\Component\Config\Definition\Processor;

final class Service implements Configurator\FactoryInterface
{
    private Processor $processor;
    private ConfigurationInterface $configuration;

    public function __construct()
    {
        $this->processor = new Processor();
        $this->configuration = new Configuration();
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
            if ($this->processor->processConfiguration($this->configuration, $config)) {
                return true;
            }
        } catch (\Exception) {
        }

        return false;
    }

    public function compile(array $config): Repository
    {
        $builder = new Builder\Logger();
        $repository = new Repository($builder);

        try {
            if (array_key_exists('inherit', $config)) {
                $builder->withLogger((new Builder\InheritBuilder())->getNode());

                return $repository;
            } else if (array_key_exists('stderr', $config)
                || (array_key_exists('type', $config) && $config['type'] === 'stderr')
            ) {
                $builder->withLogger((new Builder\StderrLogger())->getNode());
                $repository->addPackages('psr/log');

                return $repository;
            } else if (array_key_exists('blackhole', $config)
                || (array_key_exists('type', $config) && $config['type'] === 'null')
            ) {
                $builder->withLogger((new Builder\NullLogger())->getNode());
                $repository->addPackages('psr/log');

                return $repository;
            }

            if (!array_key_exists('destinations', $config)) {
                return $repository;
            }

            $monologBuilder = new Builder\MonologLogger($config['channel']);

            $repository->addPackages('psr/log', 'monolog/monolog');

            foreach ($config['destinations'] as $destination) {
                if (array_key_exists('stream', $destination)) {
                    $factory = new Factory\StreamFactory();

                    $streamRepository = $factory->compile($destination['stream']);

                    $repository->merge($streamRepository);
                    $monologBuilder->withHandlers($streamRepository->getBuilder()->getNode());
                }

                if (array_key_exists('syslog', $destination)) {
                    $factory = new Factory\SyslogFactory();

                    $syslogRepository = $factory->compile($destination['syslog']);

                    $repository->merge($syslogRepository);
                    $monologBuilder->withHandlers($syslogRepository->getBuilder()->getNode());
                }

                if (array_key_exists('logstash', $destination)) {
                    $factory = new Factory\GelfFactory();

                    $gelfRepository = $factory->compile($destination['logstash']);

                    $gelfRepository->getBuilder()->withFormatters(
                        (new LogstashFormatterBuilder($destination['logstash']['application_name']))->getNode()
                    );

                    $repository->merge($gelfRepository);
                    $monologBuilder->withHandlers($gelfRepository->getBuilder()->getNode());

                    $repository->addPackages('graylog2/gelf-php:0.1.*');
                }

                if (array_key_exists('gelf', $destination)) {
                    $factory = new Factory\GelfFactory();

                    $gelfRepository = $factory->compile($destination['gelf']);

                    $repository->merge($gelfRepository);
                    $monologBuilder->withHandlers($gelfRepository->getBuilder()->getNode());

                    $repository->addPackages('graylog2/gelf-php:1.7.*');
                }

                if (array_key_exists('elasticsearch', $destination)) {
                    $factory = new Factory\ElasticSearchFactory();

                    $gelfRepository = $factory->compile($destination['elasticsearch']);

                    $repository->merge($gelfRepository);
                    $monologBuilder->withHandlers($gelfRepository->getBuilder()->getNode());

                    $repository->addPackages('elasticsearch/elasticsearch:~7.0');
                }
            }

            $builder->withLogger($monologBuilder->getNode());

            return $repository;
        } catch (Symfony\InvalidTypeException|Symfony\InvalidConfigurationException $exception) {
            throw new Configurator\InvalidConfigurationException(
                message: $exception->getMessage(),
                previous: $exception
            );
        }
    }
}
