<?php


namespace Ccovey\RabbitMQ\Connection;


interface ConnectionInterface
{
    public function connect();
    public function getChannel(string $channelId);
}
