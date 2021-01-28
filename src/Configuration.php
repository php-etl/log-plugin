<?php declare(strict_types=1);

namespace Kiboko\Plugin\Log;

use Symfony\Component\Config;

final class Configuration implements Config\Definition\ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new Config\Definition\Builder\TreeBuilder('logger');

        /** @phpstan-ignore-next-line */
        $builder->getRootNode()
            ->children()
                ->enumNode('type')->values(['null', 'stderr'])->end()
            ->end();
        return $builder;
    }
}
