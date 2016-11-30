<?php

namespace Ccovey\RabbitMQ\Producer;

use Ccovey\RabbitMQ\ChannelInterface;
use Ccovey\RabbitMQ\Connection\ConnectionInterface;
use Ccovey\RabbitMQ\QueueDeclarer;

class Producer implements ProducerInterface
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

    public function __construct(ConnectionInterface $connection, QueueDeclarer $queueDeclarer, string $channelId = '')
    {
        $this->connection = $connection;
        $this->queueDeclarer = $queueDeclarer;
        $this->channel = $this->connection->getChannel($channelId);
    }

    public function publish(Publishable $message)
    {
        $this->queueDeclarer->declareQueue($message->getQueueName());
        $this->channel->publish($message);
    }

    public function getChannel() : ChannelInterface
    {
        return $this->channel;
    }
}
