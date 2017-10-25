<?php

namespace Fintem\MQNotificationBundle\Consumer;

use Fintem\MQNotificationBundle\Event\NotificationReceivedEvent;
use Fintem\MQNotificationBundle\MessageDataTransformerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class NotificationConsumer.
 */
class NotificationConsumer implements ConsumerInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var MessageDataTransformerInterface[]
     */
    private $transformers = [];

    /**
     * NotificationConsumer constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
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
     * {@inheritdoc}
     */
    public function execute(AMQPMessage $msg)
    {
        $encoded = $msg->getBody();
        $payload = json_decode($encoded, true);
        if (!array_key_exists('message', $payload) || !array_key_exists('data', $payload)) {
            /* @todo log? */
            return;
        }
        $message = $payload['message'];
        $data = $payload['data'];
        $data = isset($this->transformers[$message]) ? $this->transformers[$message]->transformReverse($data) : $data;

        $event = new NotificationReceivedEvent($message, $data);

        $this->dispatcher->dispatch('notification_received', $event);
        $this->dispatcher->dispatch(sprintf('notification_received.%s', $message), $event);
    }
}
