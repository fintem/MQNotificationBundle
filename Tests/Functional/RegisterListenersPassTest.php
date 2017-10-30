<?php

namespace Fintem\MQNotificationBundle\Tests\Functional;

use Fintem\MQNotificationBundle\EventDispatcher;
use Fintem\MQNotificationBundle\Functional\app\src\EventSubscriber\TestNotificationEventSubscriber;

/**
 * Class RegisterListenersPassTest.
 */
class RegisterListenersPassTest extends KernelTestCase
{
    /**
     * @test
     */
    public function taggedSubscriberShouldBeAddedToDispatcher()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        /** @var EventDispatcher $dispatcher */
        $dispatcher = $kernel->getContainer()->get('mq_notification.event_dispatcher');
        $listeners = $dispatcher->getListeners('notification_received');
        $this->assertInstanceOf(TestNotificationEventSubscriber::class, $listeners[0][0]);
    }
}