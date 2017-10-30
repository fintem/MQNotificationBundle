<?php

namespace Fintem\MQNotificationBundle\Tests\Functional\app;

use Fintem\MQNotificationBundle\MQNotificationBundle;
use OldSound\RabbitMqBundle\OldSoundRabbitMqBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class AppKernel.
 */
class AppKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return [
            new MQNotificationBundle(),
            new OldSoundRabbitMqBundle(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.yml');
    }
}