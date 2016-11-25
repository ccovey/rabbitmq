<?php

namespace Ccovey\RabbitMQ;

interface QueueRestartManagerInterface
{
    public function shouldRestart(string $queueName) : bool;

    public function restartQueue(string $queueName);
}
