<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\Log;

use Kiboko\Plugin\Log;
use PHPUnit\Framework\TestCase;

final class ServiceTest extends TestCase
{
    public function configProvider()
    {
        yield [
            'expected' => [
                'type' => 'stderr'
            ],
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
    public function testWithConfiguration(array $expected, array $actual): void
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
            'Kiboko\\Plugin\\Log\\Repository',
            $factory->compile($normalizedConfig)
        );
    }

    public function testFailToValidate(): void
    {
        $factory = new Log\Service();
        $this->assertFalse($factory->validate([]));
    }
}
