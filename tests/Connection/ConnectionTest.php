<?php

use Ccovey\RabbitMQ\Channel;
use Ccovey\RabbitMQ\Connection\Connection;
use Ccovey\RabbitMQ\Connection\ConnectionParameters;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;

class ConnectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var AMQPStreamConnection
     */
    private $streamConnection;

    public function setUp()
    {
        $connectionParameters = new ConnectionParameters('rabbithost');
        $this->connection = new ConnectionStub($connectionParameters);
        $this->streamConnection = $this->createMock(AMQPStreamConnection::class);
        $this->connection->mockConnection = $this->streamConnection;
    }

    public function testConnect()
    {
        $this->streamConnection->expects($this->once())
            ->method('reconnect');

        $this->connection->connect();
    }

    public function testGetChannel()
    {
        $channel = $this->createMock(AMQPChannel::class);
        $this->streamConnection->expects($this->once())
            ->method('channel')
            ->with('')
            ->willReturn($channel);
        $this->assertInstanceOf(Channel::class, $this->connection->getChannel());
    }
}

class ConnectionStub extends Connection
{
    public $mockConnection;

    protected function getStream()
    {
        return $this->mockConnection;
    }
}
