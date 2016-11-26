<?php

namespace Ccovey\RabbitMQ\Consumer;

use Ccovey\RabbitMQ\ChannelInterface;
use Ccovey\RabbitMQ\QueuedMessage;

interface ConsumerInterface
{
    public function consume(Consumable $consumable);
    public function getMessage(Consumable $consumable) : QueuedMessage;
    public function getChannel() : ChannelInterface;
}
