<?php

namespace Ccovey\RabbitMQ\Consumer;

use Ccovey\RabbitMQ\ChannelInterface;
use Ccovey\RabbitMQ\QueuedMessageInterface;

interface ConsumerInterface
{
    public function consume(Consumable $consumable);
    public function getMessage(Consumable $consumable);
    public function getChannel() : ChannelInterface;
    public function complete(QueuedMessageInterface $message);
    public function getSize($queue) : int;
}
