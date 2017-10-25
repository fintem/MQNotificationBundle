<?php

namespace Fintem\MQNotificationBundle\Tests\Unit\Notifier;

use Fintem\MQNotificationBundle\MessageDataTransformerInterface;
use Fintem\MQNotificationBundle\Notifier\Notifier;
use Fintem\UnitTestCase\UnitTestCase;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

/**
 * Class NotifierTest.
 */
class NotifierTest extends UnitTestCase
{
    /**
     * @test
     */
    public function notifyShouldPublishTransformedData()
    {
        $producer = $this->getBasicMock(ProducerInterface::class, null, ['publish']);
        $payload = ['message' => 'xyz_message', 'data' => ['transformed_data']];
        $producer->expects($this->once())->method('publish')->with(json_encode($payload));

        /** @var Notifier $notifier */
        $notifier = $this->getBasicMock(Notifier::class, ['producer' => $producer]);

        $methods = ['getSupportedMessages', 'transform', 'transformReverse'];
        /** @var MessageDataTransformerInterface|\PHPUnit_Framework_MockObject_MockObject $transformer */
        $transformer = $this->getBasicMock(MessageDataTransformerInterface::class, null, $methods);
        $transformer->expects($this->once())->method('getSupportedMessages')->willReturn(['xyz_message']);
        $transformer->expects($this->once())->method('transform')->willReturn(['transformed_data']);
        $notifier->addTransformer($transformer);

        $notifier->notify('xyz_message', ['data']);
    }
}
