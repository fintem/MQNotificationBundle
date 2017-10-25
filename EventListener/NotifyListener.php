<?php

namespace Fintem\MQNotificationBundle\EventListener;

use Fintem\MQNotificationBundle\Event\NotifyEvent;
use Fintem\MQNotificationBundle\Notifier\NotifierInterface;

/**
 * Class NotifyListener.
 */
class NotifyListener
{
    /**
     * @var NotifierInterface
     */
    private $notifier;
    /**
     * @var NotifyEvent[]
     */
    private $spool = [];

    /**
     * NotifyListener constructor.
     *
     * @param NotifierInterface $notifier
     */
    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    /**
     * @param NotifyEvent $event
     */
    public function notify(NotifyEvent $event)
    {
        if (!$event->notifyOnTerminate()) {
            $this->notifier->notify($event->getMessage(), $event->getData());

            return;
        }
        $this->spool[] = $event;
    }

    public function processSpool()
    {
        if (empty($this->spool)) {
            return;
        }

        foreach ($this->spool as $event) {
            $this->notifier->notify($event->getMessage(), $event->getData());
        }
        $this->spool = [];
    }
}
