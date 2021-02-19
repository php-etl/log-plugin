<?php declare(strict_types=1);

namespace Kiboko\Plugin\Log\Configuration;

use Kiboko\Plugin\Log\Configuration\Gelf;
use Psr\Log\LogLevel;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class LogstashConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder('logstash');

        $builder->getRootNode()
            ->children()
                ->scalarNode('channel')->end()
                ->enumNode('level')
                    ->info('The minimum logging level at which this handler will be triggered')
                    ->values([
                        LogLevel::DEBUG,
                        LogLevel::INFO,
                        LogLevel::NOTICE,
                        LogLevel::WARNING,
                        LogLevel::ERROR,
                        LogLevel::CRITICAL,
                        LogLevel::ALERT,
                        LogLevel::EMERGENCY,
                    ])
                ->end()
                ->append((new Gelf\AMQPConfiguration())->getConfigTreeBuilder()->getRootNode())
                ->append((new Gelf\TCPConfiguration())->getConfigTreeBuilder()->getRootNode())
            ->end();

        return $builder;
    }
}
