<?php

namespace Fintem\MQNotificationBundle\DependencyInjection\Compiler;

use Fintem\MQNotificationBundle\Consumer\NotificationConsumer;
use Fintem\MQNotificationBundle\Notifier\Notifier;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class MessageDataTransformerPass.
 */
class MessageDataTransformerPass implements CompilerPassInterface
{
    /**
     * @var array|null
     */
    private $taggedServices;

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this
            ->addTransformersToConsumer($container)
            ->addTransformersToNotifier($container);
    }

    /**
     * @param ContainerBuilder $container
     *
     * @return $this
     */
    private function addTransformersToConsumer(ContainerBuilder $container)
    {
        if ($container->has(NotificationConsumer::class)) {
            $definition = $container->findDefinition(NotificationConsumer::class);
            foreach ($this->getTransformerTaggedServices($container) as $id => $tags) {
                $definition->addMethodCall('addTransformer', [new Reference($id)]);
            }
        }

        return $this;
    }

    /**
     * @param ContainerBuilder $container
     *
     * @return $this
     */
    private function addTransformersToNotifier(ContainerBuilder $container)
    {
        if ($container->has(Notifier::class)) {
            $definition = $container->findDefinition(Notifier::class);
            foreach ($this->getTransformerTaggedServices($container) as $id => $tags) {
                $definition->addMethodCall('addTransformer', [new Reference($id)]);
            }
        }

        return $this;
    }

    /**
     * @param ContainerBuilder $container
     *
     * @return array|null
     */
    private function getTransformerTaggedServices(ContainerBuilder $container)
    {
        return null === $this->taggedServices ?
            $this->taggedServices = $container->findTaggedServiceIds('message_data_transformer') :
            $this->taggedServices;
    }
}
