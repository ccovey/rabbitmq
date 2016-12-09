<?php

namespace Ccovey\RabbitMQ;

interface QueuedMessageInterface
{
    public function getQueueName() : string;
    public function getDeliveryTag() : string;
    public function getBody() : array;
    public function getRawBody() : string;
    public function fail();
    public function isFailed() : bool;
}
