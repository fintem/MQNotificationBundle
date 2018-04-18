<?php

namespace Fintem\MQNotificationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mq_notification');
        $rootNode
            ->children()
                ->scalarNode('mq_connection_name')
                    ->isRequired()
                ->end()
                ->scalarNode('service_name')
                    ->isRequired()
                ->end()
                ->scalarNode('exchange_name')
                    ->defaultValue('notifications')
                ->end()
                ->arrayNode('qos_options')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('prefetch_size')
                            ->defaultValue(0)
                        ->end()
                        ->scalarNode('prefetch_count')
                            ->defaultValue(1)
                        ->end()
                        ->scalarNode('global')
                            ->defaultFalse()
                        ->end()
                    ->end()
                ->end()
        ;

        return $treeBuilder;
    }
}
