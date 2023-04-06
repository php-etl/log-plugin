<?php

declare(strict_types=1);

namespace functional\Kiboko\Plugin\Log\Builder;

use Kiboko\Plugin\Log\Builder;

final class NullLoggerTest extends BuilderTestCase
{
    public function testNullLogger(): void
    {
        $log = new Builder\NullLogger();

        $this->assertBuilderProducesAnInstanceOf(
            \Psr\Log\NullLogger::class,
            $log
        );
    }
}
