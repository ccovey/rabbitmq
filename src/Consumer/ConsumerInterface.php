<?php

namespace Ccovey\RabbitMQ\Consumer;

use Ccovey\RabbitMQ\ChannelInterface;

interface ConsumerInterface
{
    public function consume(Consumable $consumable);
    public function getMessage(Consumable $consumable);
    public function getChannel() : ChannelInterface;
    public function getSize($queue) : int;
}
