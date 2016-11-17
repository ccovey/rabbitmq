<?php

use Ccovey\RabbitMQ\Channel;
use Ccovey\RabbitMQ\Consumer\ConsumableParameters;
use Ccovey\RabbitMQ\Exchange;
use Ccovey\RabbitMQ\Producer\Message;
use Ccovey\RabbitMQ\Queue;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class ChannelTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Channel
     */
    private $channel;

    /**
     * @var AMQPChannel|PHPUnit_Framework_MockObject_MockObject
     */
    private $amqpChannel;

    public function setUp()
    {
        $this->amqpChannel = $this->createMock(AMQPChannel::class);
        $this->channel = new Channel($this->amqpChannel);
    }
    public function testWait()
    {
        $this->amqpChannel->expects($this->once())
            ->method('wait');
        $this->channel->wait();
    }

    public function testAcknowledge()
    {
        $this->amqpChannel->expects($this->once())
            ->method('basic_ack')
            ->with('queueName', false);
        $this->channel->acknowledge('queueName');
    }

    public function testNack()
    {
        $this->amqpChannel->expects($this->once())
            ->method('basic_nack')
            ->with('queueName', false, false);
        $this->channel->nack('queueName');
    }

    public function testNackWithMultiple()
    {
        $this->amqpChannel->expects($this->once())
            ->method('basic_nack')
            ->with('queueName', true, false);
        $this->channel->nack('queueName', true);
    }

    public function testNackWithRequeue()
    {
        $this->amqpChannel->expects($this->once())
            ->method('basic_nack')
            ->with('queueName', false, true);
        $this->channel->nack('queueName', false, true);
    }

    public function testConsume()
    {
        $consumable = new ConsumableParameters('queueName');
        $this->amqpChannel->expects($this->once())
            ->method('basic_consume')
            ->with('queueName');

        $channel = $this->channel->consume($consumable);
        $this->assertEquals($this->channel, $channel);
    }

    public function testPublish()
    {
        $body = ['foo' => 'bar'];
        $publishable = new Message(
            $body,
            'queueName',
            'not_the_default_exchange'
        );

        $this->amqpChannel->expects($this->once())
            ->method('basic_publish')
            ->with(
                new AMQPMessage(json_encode($body), ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]),
                'not_the_default_exchange',
                'queueName'
            );

        $returnedChannel = $this->channel->publish($publishable);

        $this->assertEquals($this->channel, $returnedChannel);
    }

    public function testBasicQos()
    {
        $this->amqpChannel->expects($this->once())
            ->method('basic_qos')
            ->with(
                null,
                1,
                null
            );

        $returnedChannel = $this->channel->basicQos();

        $this->assertEquals($this->channel, $returnedChannel);
    }

    public function testDelcareQueue()
    {
        $queue = new Queue('queueName');
        $this->amqpChannel->expects($this->once())
            ->method('queue_declare')
            ->with('queueName');

        $returnedChannel = $this->channel->declareQueue($queue);

        $this->assertEquals($this->channel, $returnedChannel);
    }

    public function testBindQueue()
    {
        $queue = new Queue('queueName');
        $this->amqpChannel->expects($this->once())
            ->method('queue_bind')
            ->with('queueName');

        $returnedChannel = $this->channel->bindQueue($queue);

        $this->assertEquals($this->channel, $returnedChannel);
    }

    public function testDeclareExchange()
    {
        $exchange = new Exchange('exchange', 'direct');
        $this->amqpChannel->expects($this->once())
            ->method('exchange_declare')
            ->with('exchange', 'direct');

        $returnedChannel = $this->channel->declareExchange($exchange);

        $this->assertEquals($this->channel, $returnedChannel);
    }

    public function testGetCallbacks()
    {
        $this->amqpChannel->callbacks = ['foo'];

        $returnedChannel = $this->channel->getCallbacks();

        $this->assertEquals(['foo'], $returnedChannel);
    }
}
