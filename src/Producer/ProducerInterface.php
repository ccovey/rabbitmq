<?php

namespace Ccovey\RabbitMQ\Producer;

interface ProducerInterface
{
    public function publish(Publishable $message);
}
