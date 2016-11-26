<?php

namespace Ccovey\RabbitMQ\Producer;

use Ccovey\RabbitMQ\ChannelInterface;

interface ProducerInterface
{
    public function publish(Publishable $message);
    public function getChannel() : ChannelInterface;
}
