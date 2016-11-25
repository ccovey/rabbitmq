<?php

use Ccovey\RabbitMQ\ChannelInterface;
use Ccovey\RabbitMQ\Connection\ConnectionInterface;
use Ccovey\RabbitMQ\Consumer\ConsumableParameters;
use Ccovey\RabbitMQ\Consumer\Consumer;

class ConsumerTest extends PHPUnit_Framework_TestCase
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
     * @var Consumer
     */
    private $consumer;

    public function setUp()
    {
        $this->connection = $this->createMock(ConnectionInterface::class);
        $this->channel = $this->createMock(ChannelInterface::class);
        $this->connection->expects($this->once())
            ->method('getChannel')
            ->willReturn($this->channel);
        $this->consumer = new Consumer($this->connection);
    }

    public function testConsume()
    {
        $params = new ConsumableParameters('queueName');
        $this->channel->expects($this->once())
            ->method('consume')
            ->with($params);

        $this->channel->expects($this->at(1))
            ->method('getCallbacks')
            ->willReturn(['foo']);

        $this->channel->expects($this->at(2))
            ->method('getCallbacks')
            ->willReturn(['foo']);

        $this->channel->expects($this->once())
            ->method('wait');

        $this->consumer->consume($params);
    }
}
