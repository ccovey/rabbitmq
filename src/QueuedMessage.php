<?php

namespace Ccovey\RabbitMQ;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Throwable;

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

    /**
     * @var bool
     */
    private $failed = false;

    /**
     * @var Throwable|null
     */
    private $throwable;

    public function __construct(AMQPMessage $message)
    {
        $this->message = $message;
        $this->body = json_decode($this->message->body, 1);
        $this->queueName = $this->message->delivery_info['routing_key'];
    }

    public function getQueueName() : string
    {
        return $this->queueName;
    }

    public function getDeliveryTag() : string
    {
        return $this->message->delivery_info['delivery_tag'];
    }

    public function fail(Throwable $throwable = null)
    {
        $this->throwable = $throwable;
        $this->failed = true;
    }

    public function isFailed() : bool
    {
        return $this->failed;

    }

    public function getThrowable()
    {
        return $this->throwable;
    }

    public function getBody() : array
    {
        return $this->body;
    }

    public function getRawBody() : string
    {
        return $this->message->body;
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
