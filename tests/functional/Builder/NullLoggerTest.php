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
final class NullLoggerTest extends BuilderTestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function nullLogger(): void
    {
        $log = new Builder\NullLogger();

        $this->assertBuilderProducesAnInstanceOf(
            \Psr\Log\NullLogger::class,
            $log
        );
    }
}
