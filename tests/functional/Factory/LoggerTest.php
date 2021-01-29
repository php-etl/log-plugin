<?php declare(strict_types=1);

namespace functional\Factory;

use Kiboko\Plugin\Log;
use PHPUnit\Framework\TestCase;

final class LoggerTest extends TestCase
{
    public function configProvider()
    {
        yield [
            'expected' => [
                'type' => 'stderr'
            ],
            'expected_class' => 'Kiboko\\Plugin\\Log\\Builder\\Logger',
            'actual' => [
                'logger' => [
                    'type' => 'stderr'
                ]
            ]
        ];

        yield [
            'expected' => [
                'type' => 'null'
            ],
            'expected_class' => 'Kiboko\\Plugin\\Log\\Builder\\Logger',
            'actual' => [
                'logger' => [
                    'type' => 'null'
                ]
            ]
        ];
    }

    /**
     * @dataProvider configProvider
     */
    public function testWithConfiguration(array $expected, string $expectedClass, array $actual): void
    {
        $factory = new Log\Service();
        $normalizedConfig = $factory->normalize($actual);

        $this->assertEquals(
            new Log\Configuration(),
            $factory->configuration()
        );

        $this->assertEquals(
            $expected,
            $normalizedConfig
        );

        $this->assertTrue(
            $factory->validate($actual)
        );

        $this->assertInstanceOf(
            $expectedClass,
            $factory->compile($normalizedConfig)->getBuilder()
        );
    }

    public function testFailToValidate(): void
    {
        $factory = new Log\Service();
        $this->assertFalse($factory->validate([
            'type' => 'unexpected'
        ]));
    }
}
