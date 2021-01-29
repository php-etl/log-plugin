<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\Log;

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

    public function validConfigProvider()
    {
        yield [
            'expected' => [
                'type' => 'stderr'
            ],
            'actual' => [
                'type' => 'stderr'
            ]
        ];

        yield [
            'expected' => [
                'type' => 'null'
            ],
            'actual' => [
                'type' => 'null'
            ]
        ];

        yield [
            'expected' => [
            ],
            'actual' => [
            ]
        ];
    }

    /**
     * @dataProvider validConfigProvider
     */
    public function testValidConfig($expected, $actual)
    {
        $config = new Configuration();

        $this->assertEquals(
            $expected,
            $this->processor->processConfiguration(
                $config,
                [
                    $actual
                ]
            )
        );
    }
}
