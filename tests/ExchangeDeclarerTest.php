<?php

use Ccovey\RabbitMQ\Channel;
use Ccovey\RabbitMQ\Connection\Connection;
use Ccovey\RabbitMQ\Exchange;
use Ccovey\RabbitMQ\ExchangeDeclarer;

class ExchangeDeclarerTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->connection = $this->createMock(Connection::class);
        $this->channel = $this->createMock(Channel::class);
        $this->connection->expects($this->once())
             ->method('getChannel')
             ->willReturn($this->channel);
        $this->declarer = new ExchangeDeclarer($this->connection);
    }

    public function testDeclareQueue()
    {
        $exchange = new Exchange('queueName', 'direct');
        $this->channel->expects($this->once())
            ->method('declareExchange')
            ->with($exchange);
        $this->declarer->declareExchange($exchange);
        $this->declarer->declareExchange($exchange);
    }
}
