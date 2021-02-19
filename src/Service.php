<?php declare(strict_types=1);

namespace Kiboko\Plugin\Log;

use Kiboko\Contract\Configurator;
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
            $this->processor->processConfiguration($this->configuration, $config);

            return true;
        } catch (\Exception) {
        }

        return false;
    }

    public function compile(array $config): Repository
    {
        $builder = new Builder\Logger();
        $repository = new Repository($builder);

        try {
            if (array_key_exists('stderr', $config)
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

            $repository->addPackages('psr/log', 'monolog/monolog');

            if (array_key_exists('stream', $config)) {
                $monologBuilder = new Builder\MonologLogger($config['stream']['channel']);

                $handlerBuilder = new Builder\Monolog\StreamBuilder($config['stream']['path']);

                if (array_key_exists('level', $config['stream'])) {
                    $handlerBuilder->withLevel($config['stream']['level']);
                }

                if (array_key_exists('file_permissions', $config['stream'])) {
                    $handlerBuilder->withFilePermissions($config['stream']['file_permissions']);
                }

                if (array_key_exists('use_locking', $config['stream'])) {
                    $handlerBuilder->withLocking($config['stream']['use_locking']);
                }

                $monologBuilder->withHandlers($handlerBuilder->getNode());

                $builder->withLogger($monologBuilder->getNode());
            }

            if (array_key_exists('syslog', $config)) {
                $monologBuilder = new Builder\MonologLogger($config['syslog']['channel']);

                $handlerBuilder = new Builder\Monolog\SyslogBuilder($config['syslog']['ident']);

                if (array_key_exists('level', $config['syslog'])) {
                    $handlerBuilder->withLevel($config['syslog']['level']);
                }

                if (array_key_exists('facility', $config['syslog'])) {
                    $handlerBuilder->withFacility($config['syslog']['facility']);
                }

                if (array_key_exists('logopts', $config['syslog'])) {
                    $handlerBuilder->withLogopts($config['syslog']['logopts']);
                }

                $monologBuilder->withHandlers($handlerBuilder->getNode());

                $builder->withLogger($monologBuilder->getNode());
            }

            if (array_key_exists('logstash', $config)) {
                $monologBuilder = new Builder\MonologLogger($config['logstash']['channel']);

                $handlerBuilder = new Builder\Monolog\LogstashBuilder();

                if (array_key_exists('level', $config['logstash'])) {
                    $handlerBuilder->withLevel($config['logstash']['level']);
                }

                $monologBuilder->withHandlers($handlerBuilder->getNode());

                $builder->withLogger($monologBuilder->getNode());
            }
//            if (array_key_exists('gelf', $config)) {
////                $builder->withLogger(
////                    (new Builder\MonologLogger($config['stream']['channel']))
////                        ->withHandlers(
////                            new Node\Expr\New_(
////                                class: new Node\Name\FullyQualified('Monolog\\Handler\\StreamHandler'),
////                                args: [
////                                    new Node\Arg(
////                                        new Node\Scalar\String_($config['stream']['path'])
////                                    )
////                                ]
////                            )
////                        )
////                        ->getNode()
////                );
////                $repository->addPackages('psr/log', 'monolog/monolog');
//            } else if (array_key_exists('elasticsearch', $config)) {
////                $builder->withLogger(
////                    (new Builder\MonologLogger($config['stream']['channel']))
////                        ->withHandlers(
////                            new Node\Expr\New_(
////                                class: new Node\Name\FullyQualified('Monolog\\Handler\\StreamHandler'),
////                                args: [
////                                    new Node\Arg(
////                                        new Node\Scalar\String_($config['stream']['path'])
////                                    )
////                                ]
////                            )
////                        )
////                        ->getNode()
////                );
////                $repository->addPackages('psr/log', 'monolog/monolog');
//            }

            return $repository;
        } catch (Symfony\InvalidTypeException|Symfony\InvalidConfigurationException $exception) {
            throw new Configurator\InvalidConfigurationException(
                message: $exception->getMessage(),
                previous: $exception
            );
        }
    }
}
