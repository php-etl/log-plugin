<?php

declare(strict_types=1);

namespace functional\Factory;

use Kiboko\Plugin\Log;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversNothing]
/**
 * @internal
 *
 * @coversNothing
 */
final class LoggerTest extends TestCase
{
    public static function configProvider()
    {
        yield [
            'expected' => [
                'destinations' => [
                    [
                        'elasticsearch' => [
                            'hosts' => [
                                'http://user:password@localhost:9200',
                            ],
                        ],
                    ],
                ],
            ],
            'expected_class' => \Kiboko\Plugin\Log\Builder\Logger::class,
            'actual' => [
                [
                    'destinations' => [
                        [
                            'elasticsearch' => [
                                'hosts' => [
                                    'http://user:password@localhost:9200',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        yield [
            'expected' => [
                'destinations' => [
                    [
                        'stream' => [
                            'path' => 'path/to/dev.log',
                        ],
                    ],
                ],
            ],
            'expected_class' => \Kiboko\Plugin\Log\Builder\Logger::class,
            'actual' => [
                [
                    'destinations' => [
                        [
                            'stream' => [
                                'path' => 'path/to/dev.log',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('configProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function withConfiguration(array $expected, string $expectedClass, array $actual): void
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

    #[\PHPUnit\Framework\Attributes\Test]
    public function failToValidate(): void
    {
        $factory = new Log\Service();
        $this->assertFalse($factory->validate([
            'type' => 'unexpected',
        ]));
    }
}
