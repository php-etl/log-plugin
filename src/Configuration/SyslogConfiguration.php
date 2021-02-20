<?php declare(strict_types=1);

namespace Kiboko\Plugin\Log\Configuration;

use Psr\Log\LogLevel;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class SyslogConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder('syslog');

        $builder->getRootNode()
            ->children()
                ->scalarNode('ident')->end()
                ->integerNode('facility')
                    ->info('Either one of the names of the keys in $this->facilities, or a LOG_* facility constant')
                ->end()
                ->integerNode('logopts')
                    ->info('Option flags for the openlog() call, defaults to LOG_PID')
                ->end()
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
            ->end();

        return $builder;
    }
}