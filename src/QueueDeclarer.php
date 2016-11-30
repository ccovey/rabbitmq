<?php

namespace Ccovey\RabbitMQ;

use Ccovey\RabbitMQ\Connection\Connection;

class QueueDeclarer
{
    /**
     * @var array
     */
    private $declaredQueues = [];

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var ChannelInterface
     */
    private $channel;

    public function __construct(Connection $connection, string $channelId = '')
    {
        $this->connection = $connection;
        $this->channel = $this->connection->getChannel($channelId);
    }

    /*
     * This method will be run each time we attempt to queue a message.
     * We will cache locally which queues we have already declared.
     * Declaring a queue on each iteration of a worker consuming from
     * a queue is really slow.
     */
    public function declareQueue(Queue $queue)
    {
        if (!in_array($queue, $this->declaredQueues)) {
            $this->channel->declareQueue($queue);
            $this->declaredQueues[] = $queue;
        }
    }
}
