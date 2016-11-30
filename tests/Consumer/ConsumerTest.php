<?php

use Ccovey\RabbitMQ\ChannelInterface;
use Ccovey\RabbitMQ\Connection\ConnectionInterface;
use Ccovey\RabbitMQ\Consumer\ConsumableParameters;
use Ccovey\RabbitMQ\Consumer\Consumer;
use Ccovey\RabbitMQ\QueueDeclarer;
use Ccovey\RabbitMQ\QueuedMessage;
use PhpAmqpLib\Message\AMQPMessage;

class ConsumerTest extends PHPUnit_Framework_TestCase
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

    /**
     * @var Consumer
     */
    private $consumer;

    public function setUp()
    {
        $this->connection = $this->createMock(ConnectionInterface::class);
        $this->queueDeclarer = $this->createMock(QueueDeclarer::class);
        $this->channel = $this->createMock(ChannelInterface::class);
        $this->connection->expects($this->once())
            ->method('getChannel')
            ->willReturn($this->channel);
        $this->consumer = new Consumer($this->connection, $this->queueDeclarer);
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

    public function testGetChannel()
    {
        $this->assertEquals($this->channel, $this->consumer->getChannel());
    }


    public function testGetMessage()
    {
        $mockMessage = $this->createMock(AMQPMessage::class);
        $mockMessage->delivery_info = [
            'routing_key' => 'queueName',
        ];
        $mockMessage->body = json_encode([
            'foo' => 'bar',
        ]);
        $params = new ConsumableParameters('queueName');
        $this->channel->expects($this->once())
            ->method('getMessage')
            ->with($params)
            ->willReturn($mockMessage);

        $message = $this->consumer->getMessage($params);
        $this->assertEquals(['foo' => 'bar'], $message->getBody());
        $this->assertEquals('queueName', $message->getQueueName());
        $this->assertEquals('bar', $message->foo);
    }
}
