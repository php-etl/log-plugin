<?php

declare(strict_types=1);

namespace functional\Kiboko\Plugin\Log;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use PhpParser\Builder;
use PhpParser\Node;
use PhpParser\PrettyPrinter;
use PHPUnit\Framework\Constraint\Constraint;

final class BuilderProducesAnInstanceOf extends Constraint
{
    public vfsStreamDirectory $fs;

    public function __construct(private readonly string $className)
    {
        $this->fs = vfsStream::setup('root');
    }

    /**
     * Returns a string representation of the constraint.
     */
    public function toString(): string
    {
        return sprintf(
            'is instance of %s "%s"',
            $this->getType(),
            $this->className
        );
    }

    /**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param Builder $other value or object to evaluate
     */
    protected function matches($other): bool
    {
        $printer = new PrettyPrinter\Standard();

        try {
            $filename = hash('sha512', random_bytes(512)).'.php';

            $file = new vfsStreamFile($filename);
            $file->setContent($printer->prettyPrintFile([
                new Node\Stmt\Return_($other->getNode()),
            ]));

            $this->fs->addChild($file);

            $instance = include vfsStream::url('root/'.$filename);
        } catch (\Error $exception) {
            $this->fail($other, $exception->getMessage());
        }

        return $instance instanceof $this->className;
    }

    /**
     * Returns the description of the failure.
     *
     * The beginning of failure messages is "Failed asserting that" in most
     * cases. This method should return the second part of that sentence.
     *
     * @param Builder $other evaluated value or object
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    protected function failureDescription($other): string
    {
        return sprintf(
            'The following generated code should be an instance of %s "%s"'.\PHP_EOL.'%s',
            $this->getType(),
            $this->className,
            $this->exporter()->export((new PrettyPrinter\Standard())->prettyPrint([$other->getNode()])),
        );
    }

    private function getType(): string
    {
        try {
            $reflection = new \ReflectionClass($this->className);

            if ($reflection->isInterface()) {
                return 'interface';
            }
        } catch (\ReflectionException) {
        }

        return 'class';
    }
}
