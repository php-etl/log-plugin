<?php declare(strict_types=1);

namespace Kiboko\Plugin\Log\Builder\Monolog;

use PhpParser\Builder;
use PhpParser\Node;

final class LogstashBuilder implements Builder
{
    private ?string $level;
    private ?string $host;
    private ?int $port;

    public function __construct()
    {
        $this->level = null;
        $this->host = null;
        $this->port = null;
    }

    public function withLevel(string $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function withHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function withPort(int $port): self
    {
        $this->port = $port;

        return $this;
    }

    public function getNode(): \PhpParser\Node\Expr
    {
        $arguments = [];

        if ($this->level !== null) {
            $arguments[] = new Node\Arg(
                value: new Node\Scalar\String_($this->level),
                name: new Node\Identifier('level'),
            );
        }

        return new Node\Expr\New_(
            class: new Node\Name\FullyQualified('Monolog\\Handler\\GelfHandler'),
            args: $arguments,
        );
    }
}
