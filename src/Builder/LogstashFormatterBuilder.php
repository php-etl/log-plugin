<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Log\Builder;

use PhpParser\Builder;
use PhpParser\Node;

final readonly class LogstashFormatterBuilder implements Builder
{
    public function __construct(private string $applicationName)
    {
    }

    public function getNode(): \PhpParser\Node\Expr
    {
        return new Node\Expr\New_(
            class: new Node\Name\FullyQualified(\Monolog\Formatter\LogstashFormatter::class),
            args: [
                new Node\Arg(
                    value: new Node\Scalar\String_($this->applicationName),
                    name: new Node\Identifier('applicationName')
                ),
            ],
        );
    }
}
