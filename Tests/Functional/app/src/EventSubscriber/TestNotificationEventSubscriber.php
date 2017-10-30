<?php

namespace Fintem\MQNotificationBundle\Functional\app\src\EventSubscriber;

use Fintem\MQNotificationBundle\Event\NotificationReceivedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class TestNotificationEventSubscriber.
 */
class TestNotificationEventSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'notification_received' => 'onNotificationReceived',
        ];
    }

    /**
     * @param NotificationReceivedEvent $event
     */
    public function onNotificationReceived(NotificationReceivedEvent $event)
    {
    }
}