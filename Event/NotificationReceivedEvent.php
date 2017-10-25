<?php

namespace Fintem\MQNotificationBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class NotificationReceivedEvent.
 */
class NotificationReceivedEvent extends Event
{
    /**
     * @var mixed
     */
    private $data;
    /**
     * @var string
     */
    private $message;

    /**
     * NotificationReceivedEvent constructor.
     *
     * @param string $message
     * @param mixed  $data
     */
    public function __construct(string $message, $data)
    {
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
