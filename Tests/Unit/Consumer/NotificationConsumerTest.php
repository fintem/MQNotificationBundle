<?php

namespace Fintem\MQNotificationBundle\Tests\Unit\Consumer;

use Fintem\MQNotificationBundle\Consumer\NotificationConsumer;
use Fintem\UnitTestCase\UnitTestCase;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class NotificationConsumerTest.
 */
class NotificationConsumerTest extends UnitTestCase
{
    /**
     * @test
     */
    public function executeShouldDispatchEvents()
    {
        $dispatcher = $this->getBasicMock(EventDispatcher::class, null, ['dispatch']);
        $dispatcher->expects($this->at(0))->method('dispatch')->with('notification_received');
        $dispatcher->expects($this->at(1))->method('dispatch')->with('notification_received.user_created');

        $body = json_encode(['message' => 'user_created', 'data' => ['user' => ['id' => 123]]]);

        $msg = $this->getBasicMock(AMQPMessage::class, null, ['getBody']);
        $msg->method('getBody')->willReturn($body);

        /** @var NotificationConsumer $consumer */
        $consumer = $this->getBasicMock(NotificationConsumer::class, ['dispatcher' => $dispatcher]);
        $consumer->execute($msg);
    }
}
