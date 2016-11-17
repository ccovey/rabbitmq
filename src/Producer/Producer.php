<?php

namespace Ccovey\RabbitMQ\Producer;

use Ccovey\RabbitMQ\ChannelInterface;
use Ccovey\RabbitMQ\Connection\ConnectionInterface;

class Producer implements ProducerInterface
{
    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * @var ChannelInterface
     */
    private $channel;

    public function __construct(ConnectionInterface $connection, string $channelId = '')
    {
        $this->connection = $connection;
        $this->channel = $this->connection->getChannel($channelId);
    }

    public function publish(Publishable $message)
    {
        $this->channel->publish($message);
    }
}
