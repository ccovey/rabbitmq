<?php

use Ccovey\RabbitMQ\ChannelInterface;
use Ccovey\RabbitMQ\Connection\Connection;
use Ccovey\RabbitMQ\Connection\ConnectionParameters;
use Ccovey\RabbitMQ\Consumer\ConsumableParameters;
use Ccovey\RabbitMQ\Consumer\Consumer;
use Ccovey\RabbitMQ\Exchange;
use Ccovey\RabbitMQ\Producer\Message;
use Ccovey\RabbitMQ\Producer\Producer;
use Ccovey\RabbitMQ\Queue;
use Ccovey\RabbitMQ\QueuedMessage;
use PhpAmqpLib\Exception\AMQPProtocolChannelException;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQFunctionalTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var Consumer
     */
    private $consumer;

    /**
     * @var Producer
     */
    private $producer;

    public function setUp()
    {
        $connectionParameters = new ConnectionParameters('localhost');
        $this->connection = new Connection($connectionParameters);
        $this->consumer = new Consumer($this->connection);
        $this->producer = new Producer($this->connection);
    }

    public function tearDown()
    {
        $this->connection->getChannel()
            ->deleteExchange('DifferentExchangeName');

        $this->connection->getChannel()->deleteQueue('queueName');

        $queueException = null;
        $exchangeException = null;
        try {
            $this->connection->getChannel()->declareQueue(new Queue('queueName', 'DifferentExchangeName', null, true, false));
        } catch (AMQPProtocolChannelException $queueException) {

        }

        try {
            $this->connection->getChannel()->declareExchange(new Exchange('DifferentExchangeName', 'fanout', [], true));
        } catch (AMQPProtocolChannelException $exchangeException) {

        }

        $this->assertNotNull($queueException);
        $this->assertNotNull($exchangeException);
    }

    public function testPublishConsumeAndStopRabbitMQ()
    {
        $message = new Message(['foo'], 'queueName', 'DifferentExchangeName');
        $message->setDeliveryMode(AMQPMessage::DELIVERY_MODE_NON_PERSISTENT);
        $exchange = new Exchange('DifferentExchangeName', 'fanout', [], false, false);
        $queue = new Queue('queueName', 'DifferentExchangeName', null, false, false);
        $this->connection->getChannel()
            ->declareExchange($exchange)
            ->declareQueue($queue)
            ->bindQueue($queue);

        $this->producer->publish($message);
        sleep(1); // the consumer needs a bit of delay from publish to ensure the message is available in the buffer.
        $this->assertEquals(1, $this->consumer->getSize('queueName'));
        $params = new ConsumableParameters('queueName', '01');
        $this->consumer->setRestartCheckCallable(function() {
            throw new \Exception(); // this is to stop the queue;
        });
        $this->consumer->setCallback(function (QueuedMessage $queuedMessage, ChannelInterface $channel) use ($message) {
            $this->assertEquals(AMQPMessage::DELIVERY_MODE_NON_PERSISTENT, $message->getDeliveryMode());
            $this->assertEquals($message->getBody(), $queuedMessage->getBody());
            $channel->acknowledge($queuedMessage->getDeliveryTag());
        });

        try {
            $this->consumer->consume($params);
        } catch (\Exception $e) {
        }
        $this->assertEquals('', $e->getMessage());

        // Tests that the queue exists
        $this->connection->getChannel()->declareQueue(new Queue('queueName', 'DifferentExchangeName', null, true, false));
        // Tests that the exchange exists
        $this->connection->getChannel()->declareExchange(new Exchange('DifferentExchangeName', 'fanout', [], true));
        $this->assertEquals(0, $this->consumer->getSize('queueName'));
    }
}
