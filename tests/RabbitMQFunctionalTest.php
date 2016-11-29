<?php

use Ccovey\RabbitMQ\Connection\Connection;
use Ccovey\RabbitMQ\Connection\ConnectionParameters;
use Ccovey\RabbitMQ\Consumer\ConsumableParameters;
use Ccovey\RabbitMQ\Consumer\Consumer;
use Ccovey\RabbitMQ\Exchange;
use Ccovey\RabbitMQ\Producer\Message;
use Ccovey\RabbitMQ\Producer\Producer;
use Ccovey\RabbitMQ\Queue;

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

    public function setUp()
    {
        $connectionParameters = new ConnectionParameters('localhost');
        $this->connection = new Connection($connectionParameters);
        $this->consumer = new Consumer($this->connection);
        $this->producer = new Producer($this->connection);
    }

    public function testPublishConsumeAndStopRabbitMQ()
    {
        $message = new Message(['foo'], 'queueName', 'DifferentExchangeName');
        $exchange = new Exchange('DifferentExchangeName', 'fanout');
        $queue = new Queue('queueName', 'DifferentExchangeName');
        $this->connection->getChannel()->declareExchange($exchange)->declareQueue($queue)->bindQueue($queue);
        $this->producer->publish($message);
        $this->producer->publish($message);
        $params = new ConsumableParameters('queueName', '01');
        $this->consumer->setRestartCheckCallable(function() {
            throw new \Exception(); // this is to stop the queue;
        });
        $this->consumer->setCallback(function ($message) {
            // don't need to take action here.
        });

        try {
            $this->consumer->consume($params);
        } catch (\Exception $e) {
            $this->assertEquals('', $e->getMessage());
        }

        // Tests that the queue exists
        $this->connection->getChannel()->declareQueue(new Queue('queueName', 'DifferentExchangeName', null, true));
        // Tests that the exchange exists
        $this->connection->getChannel()->declareExchange(new Exchange('DifferentExchangeName', 'fanout', [], true));
    }
}
