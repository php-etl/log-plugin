<?php

declare(strict_types=1);

namespace functional\Kiboko\Plugin\Log\Builder;

use Kiboko\Plugin\Log\Builder;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversNothing]
/**
 * @internal
 *
 * @coversNothing
 */
final class StderrLoggerTest extends BuilderTestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function stderrLogger(): void
    {
        $log = new Builder\StderrLogger();

        $this->assertBuilderProducesAnInstanceOf(
            \Psr\Log\AbstractLogger::class,
            $log
        );
    }
}
