<?php

namespace Fintem\MQNotificationBundle;

use Fintem\MQNotificationBundle\DependencyInjection\Compiler\MessageDataTransformerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class MQNotificationBundle.
 */
class MQNotificationBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new MessageDataTransformerPass());
    }
}
