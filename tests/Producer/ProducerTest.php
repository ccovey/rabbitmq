<?php

use Ccovey\RabbitMQ\ChannelInterface;
use Ccovey\RabbitMQ\Connection\ConnectionInterface;
use Ccovey\RabbitMQ\Producer\Message;
use Ccovey\RabbitMQ\Producer\Producer;
use Ccovey\RabbitMQ\QueueDeclarer;

class ProducerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Producer
     */
    private $producer;

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

    public function setUp()
    {
        $this->connection = $this->createMock(ConnectionInterface::class);
        $this->queueDeclarer = $this->createMock(QueueDeclarer::class);
        $this->channel = $this->createMock(ChannelInterface::class);
        $this->connection->expects($this->once())
            ->method('getChannel')
            ->willReturn($this->channel);
        $this->producer = new Producer($this->connection, $this->queueDeclarer);
    }

    public function testPublish()
    {
        $message = new Message(['foo' => 'bar'], 'queueName');
        $this->channel->expects($this->once())
            ->method('publish')
            ->with($message);
        $this->producer->publish($message);
    }

    public function testGetChannel()
    {
        $this->assertEquals($this->channel, $this->producer->getChannel());
    }
}
