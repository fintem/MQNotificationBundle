<?php

namespace Fintem\MQNotificationBundle\DependencyInjection;

use Fintem\MQNotificationBundle\Consumer\NotificationConsumer;
use Fintem\MQNotificationBundle\Exception\BundleNotFoundException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class MQNotificationExtension.
 */
class MQNotificationExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        if (!$container->hasExtension('old_sound_rabbit_mq')) {
            throw new BundleNotFoundException('Install and enable OldSoundRabbitMqBundle.');
        }

        $configs = $container->getExtensionConfig('mq_notification');
        $config = $this->processConfiguration(new Configuration(), $configs);

        $exchangeOptions = ['name' => $config['exchange_name'], 'type' => 'fanout'];
        $config = [
            'producers' => [
                'notify' => [
                    'connection' => $config['mq_connection_name'],
                    'exchange_options' => $exchangeOptions,
                ],
            ],
            'consumers' => [
                'notification' => [
                    'connection' => $config['mq_connection_name'],
                    'queue_options' => ['name' => $config['service_name']],
                    'qos_options' => $config['qos_options'],
                    'callback' => NotificationConsumer::class,
                    'exchange_options' => $exchangeOptions,
                ],
            ],
        ];
        $container->prependExtensionConfig('old_sound_rabbit_mq', $config);
    }
}
