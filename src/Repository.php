<?php declare(strict_types=1);

namespace Kiboko\Plugin\Log;

use Kiboko\Contract\Configurator;

final class Repository implements Configurator\RepositoryInterface
{
    /** @var Configurator\FileInterface[] */
    private array $files;
    /** @var string[] */
    private array $packages;

    public function __construct(private Builder\Logger $builder)
    {
        $this->files = [];
        $this->packages = [];
    }

    public function addFiles(Configurator\FileInterface ...$files): self
    {
        array_push($this->files, ...$files);

        return $this;
    }

    /** @return iterable<Configurator\FileInterface> */
    public function getFiles(): iterable
    {
        return $this->files;
    }

    public function addPackages(string ...$packages): self
    {
        array_push($this->packages, ...$packages);

        return $this;
    }

    /** @return iterable<string> */
    public function getPackages(): iterable
    {
        return $this->packages;
    }

    public function getBuilder(): Builder\Logger
    {
        return $this->builder;
    }

    public function merge(Configurator\RepositoryInterface $friend): self
    {
        array_push($this->files, ...$friend->getFiles());
        array_push($this->packages, ...$friend->getPackages());

        return $this;
    }
}
