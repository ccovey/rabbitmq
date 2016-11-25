<?php

namespace Ccovey\RabbitMQ;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class QueuedMessage implements QueuedMessageInterface
{
    /**
     * @var AMQPMessage
     */
    private $message;

    /**
     * @var array
     */
    private $body;

    /**
     * @var AMQPChannel
     */
    private $queueName;

    public function __construct(AMQPMessage $message)
    {
        $this->message = $message;
        $this->body = json_decode($this->message, 1);
        $this->queueName = $this->message->delivery_info['routing_key'];
    }

    public function getQueueName() : string
    {
        return $this->queueName;
    }

    /**
     * @param string $value
     *
     * @return mixed|null
     */
    public function __get(string $value)
    {
        return $this->body[$value] ?? null;
    }
}
