<?php declare(strict_types=1);

namespace Kiboko\Plugin\Log\Builder;

use PhpParser\Builder;
use PhpParser\Node;

final class MonologLogger implements Builder
{
    private iterable $handlers;

    public function __construct(private string $channel)
    {
        $this->handlers = [];
    }

    public function withHandlers(Node\Expr ...$handlers): self
    {
        array_push($this->handlers, ...$handlers);

        return $this;
    }

    public function getNode(): Node\Expr
    {
        $instance = new Node\Expr\New_(
            class: new Node\Name\FullyQualified('Monolog\\Logger'),
        );

        $instance = new Node\Expr\MethodCall(
            var: $instance,
            name: new Node\Identifier('setHandlers'),
            args: [
                new Node\Arg(
                    new Node\Expr\Array_(
                        array_map(fn (Node $handler) => new Node\Expr\ArrayItem(value: $handler), $this->handlers),
                    ),
                ),
            ],
        );

        $instance = new Node\Expr\MethodCall(
            var: $instance,
            name: new Node\Identifier('pushProcessor'),
            args: [
                new Node\Arg(
                    new Node\Expr\New_(
                        class: new Node\Name\FullyQualified('Monolog\\Processor\\PsrLogMessageProcessor')
                    )
                ),
            ],
        );

        $instance = new Node\Expr\MethodCall(
            var: $instance,
            name: new Node\Identifier('pushProcessor'),
            args: [
                new Node\Arg(
                    new Node\Expr\New_(
                        class: new Node\Name\FullyQualified('Monolog\\Processor\\MemoryUsageProcessor')
                    )
                ),
            ],
        );

        return $instance;
    }
}
