<?php

namespace Fintem\MQNotificationBundle\Tests\Unit\DependencyInjection;

use Fintem\MQNotificationBundle\DependencyInjection\Configuration;
use Fintem\UnitTestCase\UnitTestCase;
use Symfony\Component\Config\Definition\Processor;

/**
 * Class ConfigurationTest.
 */
class ConfigurationTest extends UnitTestCase
{
    /**
     * @test
     */
    public function connectionNameIsRequired()
    {
        $this->expectExceptionMessage('The child node "mq_connection_name" at path "mq_notification" must be configured.');
        $this->processConfiguration([]);
    }

    /**
     * @test
     */
    public function serviceNameIsRequired()
    {
        $this->expectExceptionMessage('The child node "service_name" at path "mq_notification" must be configured.');
        $this->processConfiguration(['mq_notification' => ['mq_connection_name' => '']]);
    }

    /**
     * @test
     */
    public function defaultExchangeName()
    {
        $config = $this
            ->processConfiguration(['mq_notification' => ['mq_connection_name' => '', 'service_name' => '']]);
        $this->assertArrayHasKey('exchange_name', $config);
        $this->assertEquals('notifications', $config['exchange_name']);
    }

    /**
     * @param array $config
     *
     * @return array
     */
    private function processConfiguration(array $config = []): array
    {
        $configuration = new Configuration();
        $processor = new Processor();

        return $processor->processConfiguration($configuration, $config);
    }
}
