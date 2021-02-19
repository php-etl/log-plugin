<?php declare(strict_types=1);

namespace Kiboko\Plugin\Log\Builder\Monolog;

use PhpParser\Builder;
use PhpParser\Node;

final class StreamBuilder implements Builder
{
    private ?string $level;
    private ?int $filePermissions;
    private ?bool $useLocking;

    public function __construct(private string $path)
    {
        $this->level = null;
    }

    public function withLevel(string $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function withFilePermissions(int $filePermissions): self
    {
        $this->filePermissions = $filePermissions;

        return $this;
    }

    public function withLocking(bool $useLocking): self
    {
        $this->useLocking = $useLocking;

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

        if ($this->filePermissions !== null) {
            $arguments[] = new Node\Arg(
                value: new Node\Scalar\LNumber($this->filePermissions, ['kind' => Node\Scalar\LNumber::KIND_OCT]),
                name: new Node\Identifier('filePermissions'),
            );
        }

        if ($this->useLocking !== null) {
            $arguments[] = new Node\Arg(
                value: new Node\Expr\ConstFetch(new Node\Name($this->useLocking ? 'true' : 'false')),
                name: new Node\Identifier('useLocking'),
            );
        }

        return new Node\Expr\New_(
            class: new Node\Name\FullyQualified('Monolog\\Handler\\StreamHandler'),
            args: $arguments,
        );
    }
}
