<?php

namespace Fintem\MQNotificationBundle\DependencyInjection\Compiler;

use Fintem\MQNotificationBundle\EventDispatcher;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass as SymfonyRegisterListenersPass;

/**
 * Class RegisterListenersPass.
 */
class RegisterListenersPass extends SymfonyRegisterListenersPass
{
    /**
     * RegisterListenersPass constructor.
     */
    public function __construct()
    {
        $dispatcherService = EventDispatcher::class;
        $listenerTag = 'mq_notification.event_listener';
        $subscriberTag = 'mq_notification.event_subscriber';
        parent::__construct($dispatcherService, $listenerTag, $subscriberTag);
    }
}