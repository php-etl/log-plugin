<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Log\Builder;

use PhpParser\Builder;
use PhpParser\Node;

final class NullLogger implements Builder
{
    public function getNode(): Node\Expr
    {
        return new Node\Expr\New_(
            class: new Node\Name\FullyQualified(\Psr\Log\NullLogger::class),
        );
    }
}
