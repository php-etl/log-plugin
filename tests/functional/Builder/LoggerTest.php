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
final class LoggerTest extends BuilderTestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function withStderrLogger(): void
    {
        $log = new Builder\Logger(
            (new Builder\StderrLogger())->getNode()
        );

        $this->assertBuilderProducesAnInstanceOf(
            \Psr\Log\AbstractLogger::class,
            $log
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function withoutSpecifiedLogger(): void
    {
        $log = new Builder\Logger();

        $this->assertBuilderProducesAnInstanceOf(
            \Psr\Log\NullLogger::class,
            $log
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function addingStderrLogger(): void
    {
        $log = new Builder\Logger();

        $log->withLogger(
            (new Builder\StderrLogger())->getNode()
        );

        $this->assertBuilderProducesAnInstanceOf(
            \Psr\Log\AbstractLogger::class,
            $log
        );
    }
}
