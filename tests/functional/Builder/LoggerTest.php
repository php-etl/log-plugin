<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\Log\Builder;

use Kiboko\Plugin\Log\Builder;

final class LoggerTest extends BuilderTestCase
{
    public function testWithStderrLogger(): void
    {
        $log = new Builder\Logger(
            (new Builder\StderrLogger())->getNode()
        );

        $this->assertBuilderProducesAnInstanceOf(
            'Psr\\Log\\AbstractLogger',
            $log
        );
    }

    public function testWithoutSpecifiedLogger(): void
    {
        $log = new Builder\Logger();

        $this->assertBuilderProducesAnInstanceOf(
            'Psr\\Log\\NullLogger',
            $log
        );
    }

    public function testAddingStderrLogger(): void
    {
        $log = new Builder\Logger();

        $log->withLogger(
            (new Builder\StderrLogger())->getNode()
        );

        $this->assertBuilderProducesAnInstanceOf(
            'Psr\\Log\\AbstractLogger',
            $log
        );
    }
}
