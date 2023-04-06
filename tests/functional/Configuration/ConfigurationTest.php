<?php

declare(strict_types=1);

namespace functional\Kiboko\Plugin\Log\Configuration;

use Kiboko\Plugin\Log\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config;

class ConfigurationTest extends TestCase
{
    private ?Config\Definition\Processor $processor = null;

    protected function setUp(): void
    {
        $this->processor = new Config\Definition\Processor();
    }

    public static function validConfigProvider(): \Generator
    {
        yield [
            'expected' => [
                'destinations' => [],
            ],
            'actual' => [
                'destinations' => [],
            ],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('validConfigProvider')]
    public function testValidConfig($expected, $actual): void
    {
        $config = new Configuration();

        $this->assertEquals(
            $expected,
            $this->processor->processConfiguration(
                $config,
                [
                    $actual,
                ]
            )
        );
    }
}
