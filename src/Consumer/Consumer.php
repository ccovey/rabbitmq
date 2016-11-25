<?php

namespace Ccovey\RabbitMQ\Consumer;

use Ccovey\RabbitMQ\ChannelInterface;
use Ccovey\RabbitMQ\Connection\ConnectionInterface;
use Ccovey\RabbitMQ\QueuedMessage;
use Ccovey\RabbitMQ\QueueRestartManagerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class Consumer implements ConsumerInterface
{
    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * @var QueueRestartManagerInterface
     */
    private $queueRestartManager;

    /**
     * @var ChannelInterface
     */
    private $channel;

    /**
     * @var callable
     */
    private $callback;

    public function __construct(ConnectionInterface $connection, QueueRestartManagerInterface $queueRestartManager, string $channelId = '')
    {
        $this->connection = $connection;
        $this->queueRestartManager = $queueRestartManager;
        $this->channel = $this->connection->getChannel($channelId);
    }

    public function setCallback(callable $callback)
    {
        $this->callback = $callback;
    }

    public function consume(Consumable $consumable)
    {
        $consumable->setCallback([$this, 'process']);
        $this->channel->consume($consumable);

        while (count($this->channel->getCallbacks())) {
            $this->channel->wait();
        }
    }

    protected function process(AMQPMessage $message)
    {
        $queuedMessage = new QueuedMessage($message);

        call_user_func($this->callback, $queuedMessage);

        $this->queueRestartManager->shouldRestart($queuedMessage->getQueueName());
    }
}
