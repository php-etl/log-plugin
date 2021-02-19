<?php declare(strict_types=1);

namespace Kiboko\Plugin\Log\Builder\Monolog;

use PhpParser\Builder;
use PhpParser\Node;

final class ElasticSearchBuilder implements Builder
{
    private ?string $level;

    public function __construct(private string $path)
    {
        $this->level = null;
    }

    public function withLevel(string $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getNode(): \PhpParser\Node\Expr
    {
        $arguments = [
            new Node\Arg(
                value: new Node\Scalar\String_($this->path),
                name: new Node\Identifier('path'),
            ),
        ];

        if ($this->level !== null) {
            $arguments[] = new Node\Arg(
                value: new Node\Scalar\String_($this->level),
                name: new Node\Identifier('level'),
            );
        }

        return new Node\Expr\New_(
            class: new Node\Name\FullyQualified('Monolog\\Handler\\ElasticSearchHandler'),
            args: $arguments,
        );
    }
}
