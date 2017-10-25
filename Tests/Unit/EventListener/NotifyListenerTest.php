<?php

namespace Fintem\MQNotificationBundle\Tests\Unit\EventListener;

use Fintem\MQNotificationBundle\Event\NotifyEvent;
use Fintem\MQNotificationBundle\EventListener\NotifyListener;
use Fintem\MQNotificationBundle\Notifier\Notifier;
use Fintem\UnitTestCase\UnitTestCase;

/**
 * Class NotifyListenerTest.
 */
class NotifyListenerTest extends UnitTestCase
{
    /**
     * @test
     */
    public function notifyShouldAddToSpool()
    {
        $notifier = $this->getBasicMock(Notifier::class, null, ['notify']);
        $notifier->expects($this->never())->method('notify');

        /** @var NotifyListener $listener */
        $listener = $this->getBasicMock(NotifyListener::class, ['notifier' => $notifier]);

        /** @var NotifyEvent|\PHPUnit_Framework_MockObject_MockObject $event */
        $event = $this->getBasicMock(NotifyEvent::class, null, ['notifyOnTerminate']);
        $event->expects($this->once())->method('notifyOnTerminate')->willReturn(true);

        $listener->notify($event);
    }

    /**
     * @test
     */
    public function processSpoolShouldNotifyItemsInSpool()
    {
        $notifier = $this->getBasicMock(Notifier::class, null, ['notify']);
        $notifier->expects($this->exactly(2))->method('notify')->with('message', ['data']);

        /** @var NotifyListener $listener */
        $listener = $this->getBasicMock(NotifyListener::class, ['notifier' => $notifier]);

        /** @var NotifyEvent|\PHPUnit_Framework_MockObject_MockObject $event */
        $event = $this->getBasicMock(NotifyEvent::class, null, ['notifyOnTerminate', 'getMessage', 'getData']);
        $event->method('notifyOnTerminate')->willReturn(true);
        $event->method('getMessage')->willReturn('message');
        $event->method('getData')->willReturn(['data']);

        $listener->notify($event);
        $listener->notify($event);
        $listener->processSpool();
    }
}
