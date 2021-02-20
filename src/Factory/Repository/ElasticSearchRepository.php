<?php declare(strict_types=1);

namespace Kiboko\Plugin\Log\Factory\Repository;

use Kiboko\Contract\Configurator;
use Kiboko\Plugin\Log\Builder;
use Kiboko\Plugin\Log\RepositoryTrait;

final class ElasticSearchRepository implements Configurator\RepositoryInterface
{
    use RepositoryTrait;

    public function __construct(private Builder\Monolog\ElasticSearchBuilder $builder)
    {
        $this->files = [];
        $this->packages = [];
    }

    public function getBuilder(): Builder\Monolog\ElasticSearchBuilder
    {
        return $this->builder;
    }
}
