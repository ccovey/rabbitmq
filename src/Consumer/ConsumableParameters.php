<?php

namespace Ccovey\RabbitMQ\Consumer;

class ConsumableParameters implements Consumable
{
    /**
     * @var string
     */
    private $queueName;

    /**
     * @var string
     */
    private $consumerTag = '';

    private $callback;

    /**
     * @var bool
     */
    private $noLocal = false;

    /**
     * @var bool
     */
    private $noAck = false;

    /**
     * @var bool
     */
    private $exclusive = false;

    /**
     * @var bool
     */
    private $noWait = false;

    /**
     * @var int
     */
    private $ticket;

    /**
     * @var array
     */
    private $arguments = [];

    public function __construct(
        string $queueName,
        string $consumerTag = '',
        $callback = null,
        bool $noLocal = false,
        bool $noAck = false,
        bool $exclusive = false,
        bool $noWait = false,
        $ticket = null,
        array $arguments = []
    ) {
        $this->queueName = $queueName;
        $this->consumerTag = $consumerTag;
        $this->callback = $callback;
        $this->noLocal = $noLocal;
        $this->noAck = $noAck;
        $this->exclusive = $exclusive;
        $this->noWait = $noWait;
        $this->ticket = $ticket;
        $this->arguments = $arguments;
    }

    /**
     * @return string
     */
    public function getQueueName() : string
    {
        return $this->queueName;
    }

    public function getConsumerTag() : string
    {
        return $this->consumerTag;
    }

    public function getCallback()
    {
        return $this->callback;
    }

    public function isNoLocal() : bool
    {
        return $this->noLocal;
    }

    public function isNoAck() : bool
    {
        return $this->noAck;
    }

    public function isExclusive() : bool
    {
        return $this->exclusive;
    }

    public function isNoWait() : bool
    {
        return $this->noWait;
    }

    public function getTicket()
    {
        return $this->ticket;
    }

    public function getArguments() : array
    {
        return $this->arguments;
    }
}
