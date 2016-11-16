<?php

namespace Ccovey\RabbitMQ;

use PhpAmqpLib\Wire\AMQPTable;

class Queue
{
    /**
     * @var string
     */
    private $queueName;

    /**
     * @var AMQPTable
     */
    private $arguments;

    /**
     * @var string
     */
    private $exchange;

    /**
     * @var bool
     */
    private $passive;

    /**
     * @var bool
     */
    private $durable;

    /**
     * @var bool
     */
    private $exclusive;

    /**
     * @var bool
     */
    private $autoDelete;

    /**
     * @var bool
     */
    private $noWait;

    private $ticket;

    public function __construct(
        string $queueName,
        string $exchange = '',
        AMQPTable $arguments = null,
        bool $passive = false,
        bool $durable = true,
        bool $exclusive = false,
        bool $autoDelete = false,
        bool $noWait = false,
        int $ticket = null
    ) {
        $this->queueName = $queueName;
        $this->exchange = $exchange;
        $this->arguments = $arguments;
        $this->passive = $passive;
        $this->durable = $durable;
        $this->exclusive = $exclusive;
        $this->autoDelete = $autoDelete;
        $this->noWait = $noWait;
        $this->ticket = $ticket;
    }

    public function getQueueName() : string
    {
        return $this->queueName;
    }

    /**
     * @return AMQPTable|null
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    public function isPassive() : bool
    {
        return $this->passive;
    }

    public function isDurable() : bool
    {
        return $this->durable;
    }

    public function isExclusive() : bool
    {
        return $this->exclusive;
    }

    public function isAutoDelete() : bool
    {
        return $this->autoDelete;
    }

    public function isNoWait() : bool
    {
        return $this->noWait;
    }

    public function getTicket()
    {
        return $this->ticket;
    }

    public function getExchange() : string
    {
        return $this->exchange;
    }
}
