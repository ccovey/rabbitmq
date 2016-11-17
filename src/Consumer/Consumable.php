<?php

namespace Ccovey\RabbitMQ\Consumer;

interface Consumable
{
    public function getQueueName() : string;

    public function getConsumerTag() : string;

    public function getCallback();

    public function setCallback($callback);

    public function isNoLocal() : bool;

    public function isNoAck() : bool;

    public function isExclusive() : bool;

    public function isNoWait() : bool;

    public function getTicket();

    public function getArguments() : array;
}
