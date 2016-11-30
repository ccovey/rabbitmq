<?php

namespace Ccovey\RabbitMQ\Config;

use PhpAmqpLib\Wire\AMQPTable;

class QueueConfig
{
    /**
     * @var array
     */
    private $configuration;

    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getExchange() : string
    {
        return $this->configuration['exchange'] ?? '';
    }

    /**
     * @return null|AMQPTable
     */
    public function getAmqpTable()
    {
        $amqpTable = null;
        if (isset($this->configuration['amqp_table'])) {
            $amqpTable = new AMQPTable();
            foreach ($this->configuration['aqmp_table'] as $key => $tableParam) {
                $amqpTable->set($key, $tableParam);
            }
        }

        return $amqpTable;
    }

    public function isDurable() : bool
    {
        return $this->configuration['durable'] ?? true;
    }

    public function isExclusive() : bool
    {
        return $this->configuration['exclusive'] ?? false;
    }

    public function isAutoDelete() : bool
    {
        return $this->configuration['autoDelete'] ?? false;
    }

    public function isNoWait() : bool
    {
        return $this->configuration['noWait'] ?? false;
    }

    public function getTicket()
    {
        return $this->configuration['ticket'] ?? null;
    }
}
