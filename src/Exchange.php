<?php

namespace Ccovey\RabbitMQ;

class Exchange
{
    /**
     * @var string
     */
    private $exchange;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array|null
     */
    private $arguments;

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
    private $autoDelete;

    /**
     * @var bool
     */
    private $internal;

    /**
     * @var bool
     */
    private $noWait;

    /**
     * @var int
     */
    private $ticket;

    public function __construct(
        string $exchange,
        string $type,
        array $arguments = null,
        bool $passive = false,
        bool $durable = true,
        bool $autoDelete = false,
        bool $internal = false,
        bool $noWait = false,
        $ticket = null
    ) {
        $this->exchange = $exchange;
        $this->type = $type;
        $this->arguments = $arguments;
        $this->passive = $passive;
        $this->durable = $durable;
        $this->autoDelete = $autoDelete;
        $this->internal = $internal;
        $this->noWait = $noWait;
        $this->ticket = $ticket;
    }

    public function getExchange() : string
    {
        return $this->exchange;
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function getArguments() : array
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

    public function isAutoDelete() : bool
    {
        return $this->autoDelete;
    }

    public function isInternal() : bool
    {
        return $this->internal;
    }

    public function isNoWait() : bool
    {
        return $this->noWait;
    }

    public function getTicket()
    {
        return $this->ticket;
    }
}
