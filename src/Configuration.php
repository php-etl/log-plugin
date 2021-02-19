<?php declare(strict_types=1);

namespace Kiboko\Plugin\Log;

use Kiboko\Plugin\Log;
use Symfony\Component\Config;

final class Configuration implements Config\Definition\ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new Config\Definition\Builder\TreeBuilder('logger');

        /** @phpstan-ignore-next-line */
        $builder->getRootNode()
            ->children()
                ->enumNode('type')
                    ->values(['null', 'stderr'])
                    ->setDeprecated('php-etl/logger-plugin', '0.1.x-dev', 'This notation is deprecated and will be removed in am upcoming version, please use top-level notation instead.')
                ->end()
                ->scalarNode('blackhole')
                    ->validate()
                        ->ifTrue(fn ($value) => $value !== null)
                        ->thenInvalid('No value can be accepted in the blackhole logger, please set null.')
                    ->end()
                ->end()
                ->scalarNode('stderr')
                    ->validate()
                        ->ifTrue(fn ($value) => $value !== null)
                        ->thenInvalid('No value can be accepted in the stderr logger, please set null.')
                    ->end()
                ->end()
                ->append((new Log\Configuration\StreamConfiguration())->getConfigTreeBuilder()->getRootNode())
                ->append((new Log\Configuration\SyslogConfiguration())->getConfigTreeBuilder()->getRootNode())
                ->append((new Log\Configuration\GelfConfiguration())->getConfigTreeBuilder()->getRootNode())
                ->append((new Log\Configuration\ElasticSearchConfiguration())->getConfigTreeBuilder()->getRootNode())
                ->append((new Log\Configuration\LogstashConfiguration())->getConfigTreeBuilder()->getRootNode())
//                ->arrayNode('syslog_udp')->end()
//                ->arrayNode('slack')->end()
//                ->arrayNode('slack_webhook')->end()
            ->end();
        return $builder;
    }
}
