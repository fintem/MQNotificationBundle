<?php

namespace Fintem\MQNotificationBundle\Tests\Unit\DependencyInjection;

use Fintem\MQNotificationBundle\DependencyInjection\MQNotificationExtension;
use Fintem\MQNotificationBundle\Exception\BundleNotFoundException;
use Fintem\UnitTestCase\UnitTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class MQNotificationExtensionTest.
 */
class MQNotificationExtensionTest extends UnitTestCase
{
    /**
     * @test
     */
    public function prependShouldPrependRabbitMQBundleConfig()
    {
        /** @var ContainerBuilder|\PHPUnit_Framework_MockObject_MockObject $container */
        $container = $this->getBasicMock(ContainerBuilder::class, null, ['hasExtension', 'getExtensionConfig', 'prependExtensionConfig']);
        $container->expects($this->once())
            ->method('hasExtension')
            ->with('old_sound_rabbit_mq')
            ->willReturn(true)
        ;

        $container->expects($this->once())
            ->method('getExtensionConfig')
            ->with('mq_notification')
            ->willReturn(['mq_notification' => [
                'mq_connection_name' => 'default',
                'service_name' => 'custom_app',
                'qos_options' => ['global' => true]
            ]])
        ;

        $exchangeOptions = ['name' => 'notifications', 'type' => 'fanout'];
        $rabbitMqBundleConfig = [
            'producers' => [
                'notify' => [
                    'connection' => 'default',
                    'exchange_options' => $exchangeOptions,
                ],
            ],
            'consumers' => [
                'notification' => [
                    'connection' => 'default',
                    'queue_options' => ['name' => 'custom_app'],
                    'qos_options' => ['prefetch_size' => 0, 'prefetch_count' => 1, 'global' => true],
                    'callback' => 'Fintem\MQNotificationBundle\Consumer\NotificationConsumer',
                    'exchange_options' => $exchangeOptions,
                ],
            ],
        ];
        $container->expects($this->once())
            ->method('prependExtensionConfig')
            ->with('old_sound_rabbit_mq', $rabbitMqBundleConfig)
        ;

        $extension = new MQNotificationExtension();
        $extension->prepend($container);
    }

    /**
     * @test
     */
    public function prependShouldThrowBundleNotFoundException()
    {
        /** @var MQNotificationExtension $extension */
        $extension = $this->getBasicMock(MQNotificationExtension::class);

        /** @var ContainerBuilder|\PHPUnit_Framework_MockObject_MockObject $container */
        $container = $this->getBasicMock(ContainerBuilder::class, null, ['getParameter']);
        $container->method('getParameter')->with('kernel.bundles')->willReturn([]);

        $this->expectException(BundleNotFoundException::class);
        $extension->prepend($container);
    }
}
