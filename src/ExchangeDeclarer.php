<?php

namespace Ccovey\RabbitMQ;

use Ccovey\RabbitMQ\Connection\Connection;

class ExchangeDeclarer
{
    /**
     * @var array
     */
    private $declaredExchanges = [];

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
     * We will cache locally which exchanges we have already declared.
     * Declaring an exchange on each iteration of a worker consuming from
     * a queue is really slow.
     */
    public function declareExchange(Exchange $exchange)
    {
        if (!in_array($exchange, $this->declaredExchanges)) {
            $this->channel->declareExchange($exchange);
            $this->declaredExchanges[] = $exchange;
        }
    }
}
