<?php

namespace Fintem\MQNotificationBundle\Notifier;

use Fintem\MQNotificationBundle\MessageDataTransformerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

/**
 * Class Notifier.
 */
class Notifier implements NotifierInterface
{
    /**
     * @var ProducerInterface
     */
    private $producer;
    /**
     * @var MessageDataTransformerInterface[]
     */
    private $transformers = [];

    /**
     * Notifier constructor.
     *
     * @param ProducerInterface $producer
     */
    public function __construct(ProducerInterface $producer)
    {
        $this->producer = $producer;
    }

    /**
     * @param MessageDataTransformerInterface $transformer
     */
    public function addTransformer(MessageDataTransformerInterface $transformer)
    {
        $byMessage = array_fill_keys($transformer->getSupportedMessages(), $transformer);
        $this->transformers = array_merge($this->transformers, $byMessage);
    }

    /**
     * @param string $message
     * @param mixed  $data
     */
    public function notify(string $message, $data)
    {
        $transformedData = isset($this->transformers[$message]) ?
            $this->transformers[$message]->transform($data) :
            $data;
        $payload = ['message' => $message, 'data' => $transformedData];
        $encoded = json_encode($payload);

        $this->producer->publish($encoded);
    }
}
