<?php

namespace Fintem\MQNotificationBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class NotifyEvent.
 */
class NotifyEvent extends Event
{
    const NAME = 'mq_notification.notify';
    /**
     * @var mixed
     */
    private $data;
    /**
     * @var string
     */
    private $message;
    /**
     * @var bool
     */
    private $notifyOnTerminate;

    /**
     * NotifyEvent constructor.
     *
     * @param string $message
     * @param mixed  $data
     * @param bool   $notifyOnTerminate
     */
    public function __construct(string $message, $data = null, bool $notifyOnTerminate = false)
    {
        $this->message = $message;
        $this->data = $data;
        $this->notifyOnTerminate = $notifyOnTerminate;
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

    /**
     * @return bool
     */
    public function notifyOnTerminate(): bool
    {
        return $this->notifyOnTerminate;
    }
}
