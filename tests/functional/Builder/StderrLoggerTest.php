<?php

declare(strict_types=1);

namespace functional\Kiboko\Plugin\Log\Builder;

use Kiboko\Plugin\Log\Builder;

final class StderrLoggerTest extends BuilderTestCase
{
    public function testStderrLogger(): void
    {
        $log = new Builder\StderrLogger();

        $this->assertBuilderProducesAnInstanceOf(
            \Psr\Log\AbstractLogger::class,
            $log
        );
    }
}
