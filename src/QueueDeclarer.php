<?php

namespace Ccovey\RabbitMQ;

use Ccovey\RabbitMQ\Config\QueueConfig;
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
     * @var QueueConfig
     */
    private $config;

    /**
     * @var ChannelInterface
     */
    private $channel;

    public function __construct(Connection $connection, QueueConfig $config, string $channelId = '')
    {
        $this->connection = $connection;
        $this->config = $config;
        $this->channel = $this->connection->getChannel($channelId);
    }

    /*
     * This method will be run each time we attempt to queue a message.
     * We will cache locally which queues we have already declared.
     * Declaring a queue on each iteration of a worker consuming from
     * a queue is really slow.
     */
    public function declareQueue($queueName)
    {
        if (!in_array($queueName, $this->declaredQueues)) {
            $queue = new Queue(
                $queueName,
                $this->config->getExchange(),
                $this->config->getAmqpTable(),
                false,
                $this->config->isDurable(),
                $this->config->isExclusive(),
                $this->config->isAutoDelete(),
                $this->config->isNoWait(),
                $this->config->getTicket()
            );

            $this->channel->declareQueue($queue);
            $this->channel->bindQueue($queue);
            $this->declaredQueues[] = $queueName;
        }
    }
}
