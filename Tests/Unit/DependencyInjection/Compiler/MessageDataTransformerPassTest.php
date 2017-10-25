<?php

namespace Fintem\MQNotificationBundle\Tests\Unit\DependencyInjection\Compiler;

use Fintem\MQNotificationBundle\DependencyInjection\Compiler\MessageDataTransformerPass;
use Fintem\UnitTestCase\UnitTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Class MessageDataTransformerPassTest.
 */
class MessageDataTransformerPassTest extends UnitTestCase
{
    /**
     * @test
     */
    public function processShouldAddTransformersToServices()
    {
        $definition = $this->getBasicMock(Definition::class, null, ['addMethodCall']);
        $definition->expects($this->exactly(4))->method('addMethodCall');

        /** @var ContainerBuilder|\PHPUnit_Framework_MockObject_MockObject $container */
        $container = $this->getBasicMock(
            ContainerBuilder::class,
            null,
            ['has', 'findDefinition', 'findTaggedServiceIds', 'getTransformerTaggedServices']
        );
        $container->method('has')->willReturn(true);
        $container->method('findDefinition')->willReturn($definition);
        $taggedServices = ['id_1' => '', 'id_2' => ''];
        $container->expects($this->once())->method('findTaggedServiceIds')->willReturn($taggedServices);

        /** @var MessageDataTransformerPass $pass */
        $pass = $this->getBasicMock(MessageDataTransformerPass::class);
        $pass->process($container);
    }
}
