<?php

use Ccovey\RabbitMQ\Channel;
use Ccovey\RabbitMQ\Config\QueueConfig;
use Ccovey\RabbitMQ\Connection\Connection;
use Ccovey\RabbitMQ\Queue;
use Ccovey\RabbitMQ\QueueDeclarer;

class QueueDeclarerTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->connection = $this->createMock(Connection::class);
        $this->queueConfig = $this->createMock(QueueConfig::class);
        $this->channel = $this->createMock(Channel::class);
        $this->connection->expects($this->once())
            ->method('getChannel')
            ->willReturn($this->channel);
        $this->declarer = new QueueDeclarer($this->connection, $this->queueConfig);
    }

    public function testDeclareQueue()
    {
        $queue = 'queueName';
        $this->channel->expects($this->once())
            ->method('declareQueue')
            ->with(new Queue($queue, '', null, false, false, false, false));
        $this->declarer->declareQueue($queue);
        $this->declarer->declareQueue($queue);
    }
}
