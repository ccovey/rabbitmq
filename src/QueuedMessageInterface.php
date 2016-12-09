<?php

namespace Ccovey\RabbitMQ;

use Throwable;

interface QueuedMessageInterface
{
    public function getQueueName() : string;
    public function getDeliveryTag() : string;
    public function getBody() : array;
    public function getRawBody() : string;
    public function fail(Throwable $throwable = null);
    public function isFailed() : bool;
}
