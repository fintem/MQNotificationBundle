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
        ;

        return $treeBuilder;
    }
}
