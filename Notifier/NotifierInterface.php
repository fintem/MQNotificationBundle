<?php

namespace Fintem\MQNotificationBundle\Notifier;

/**
 * Interface NotifierInterface.
 */
interface NotifierInterface
{
    /**
     * @param string $message
     * @param mixed  $data
     */
    public function notify(string $message, $data);
}
