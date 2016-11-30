<?php

namespace Ccovey\RabbitMQ\Consumer;

use Ccovey\RabbitMQ\ChannelInterface;
use Ccovey\RabbitMQ\Connection\ConnectionInterface;
use Ccovey\RabbitMQ\Queue;
use Ccovey\RabbitMQ\QueueDeclarer;
use Ccovey\RabbitMQ\QueuedMessage;
use Ccovey\RabbitMQ\QueuedMessageInterface;
use PhpAmqpLib\Message\AMQPMessage;

class Consumer implements ConsumerInterface
{
    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * @var QueueDeclarer
     */
    private $queueDeclarer;

    /**
     * @var ChannelInterface
     */
    private $channel;

    /**
     * @var callable
     */
    private $callback;

    /**
     * @var callable
     */
    private $restartCheckCallable;

    public function __construct(ConnectionInterface $connection, QueueDeclarer $queueDeclarer, string $channelId = '')
    {
        $this->connection = $connection;
        $this->queueDeclarer = $queueDeclarer;
        $this->channel = $this->connection->getChannel($channelId);
    }

    public function setCallback(callable $callback = null)
    {
        $this->callback = $callback;
    }

    public function setRestartCheckCallable(callable $callable)
    {
        $this->restartCheckCallable = $callable;
    }

    public function consume(Consumable $consumable)
    {
        $this->queueDeclarer->declareQueue($consumable->getQueueName());
        $consumable->setCallback([$this, 'process']);
        $this->channel->consume($consumable);

        while (count($this->channel->getCallbacks())) {
            $this->channel->wait();
        }
    }

    public function getMessage(Consumable $consumable) : QueuedMessage
    {
        $this->queueDeclarer->declareQueue($consumable->getQueueName());
        $message = $this->channel->getMessage($consumable);

        if ($message) {
            return new QueuedMessage($message);
        }
    }

    public function getChannel() : ChannelInterface
    {
        return $this->channel;
    }

    public function process(AMQPMessage $message)
    {
        $queuedMessage = new QueuedMessage($message);

        call_user_func($this->callback, $queuedMessage, $this->channel);

        $this->checkRestart($queuedMessage);
    }

    public function getSize($queue) : int
    {
        $queueParams = new Queue(
            $queue,
            '',
            null,
            true
        );

        return $this->channel->getQueueSize($queueParams);
    }

    private function checkRestart(QueuedMessageInterface $queuedMessage)
    {
        if ($this->restartCheckCallable) {
            ($this->restartCheckCallable)($queuedMessage);
        }
    }
}
