<?php

use Ccovey\RabbitMQ\Config\QueueConfig;
use PhpAmqpLib\Wire\AMQPTable;

class QueueConfigTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var QueueConfig
     */
    private $queueConfig;

    public function setUp()
    {
        $this->queueConfig = new QueueConfig([
            'exchange' => 'NonDefaultExchange',
            'durable' => true,
            'exclusive' => true,
            'autoDelete' => true,
            'noWait' => true,
            'ticket' => 123,
            'amqp_table' => [
                'x-delay' => 1234567,
                'extra-header' => 'bar',
            ],
        ]);
    }

    public function testGetExchange()
    {
        $this->assertEquals('NonDefaultExchange', $this->queueConfig->getExchange());
    }

    public function testIsDurable()
    {
        $this->assertTrue($this->queueConfig->isDurable());
    }

    public function testIsExclusive()
    {
        $this->assertTrue($this->queueConfig->isExclusive());
    }

    public function testIsAutoDelete()
    {
        $this->assertTrue($this->queueConfig->isAutoDelete());
    }

    public function testIsNoWait()
    {
        $this->assertTrue($this->queueConfig->isNoWait());
    }

    public function testGetTicket()
    {
        $this->assertEquals(123, $this->queueConfig->getTicket());
    }

    public function testGetAmqpTable()
    {
        $this->assertEquals(new AMQPTable([
            'x-delay' => 1234567,
            'extra-header' => 'bar',
        ]), $this->queueConfig->getAmqpTable());
    }
}
