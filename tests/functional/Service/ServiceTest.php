<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\Log\Service;

use Kiboko\Contract\Configurator\InvalidConfigurationException;
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
            'expected_class' => 'Kiboko\\Plugin\\Log\\Builder\\Logger',
            'actual' => [
                'logger' => [
                    'type' => 'stderr'
                ],
            ],
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
