<?php

namespace Fintem\MQNotificationBundle;

/**
 * Interface MessageDataTransformerInterface.
 */
interface MessageDataTransformerInterface
{
    /**
     * @return array
     */
    public function getSupportedMessages(): array;

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    public function transform($data);

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    public function transformReverse($data);
}
