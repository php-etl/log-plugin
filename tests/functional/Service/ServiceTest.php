<?php

declare(strict_types=1);

namespace functional\Kiboko\Plugin\Log\Service;

use Kiboko\Plugin\Log;
use PHPUnit\Framework\TestCase;

final class ServiceTest extends TestCase
{
    public static function configProvider(): \Generator
    {
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
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('configProvider')]
    public function testWithConfigurationAndProcessor(array $expected, string $expectedClass, array $actual): void
    {
        $service = new Log\Service();
        $normalizedConfig = $service->normalize($actual);

        $this->assertEquals(
            new Log\Configuration(),
            $service->configuration()
        );

        $this->assertEquals(
            $expected,
            $normalizedConfig
        );

        $this->assertTrue($service->validate($actual));

        $this->assertInstanceOf(
            $expectedClass,
            $service->compile($normalizedConfig)->getBuilder()
        );
    }
}
