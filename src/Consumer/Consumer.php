<?php

namespace Ccovey\RabbitMQ\Consumer;

use Ccovey\RabbitMQ\ChannelInterface;
use Ccovey\RabbitMQ\Connection\ConnectionInterface;

class Consumer implements ConsumerInterface
{
    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * @var ChannelInterface
     */
    private $channel;

    /**
     * @var callable
     */
    private $callback;

    public function __construct(ConnectionInterface $connection, string $channelId = '')
    {
        $this->connection = $connection;
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
}
