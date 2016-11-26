<?php


namespace Ccovey\RabbitMQ\Connection;


use Ccovey\RabbitMQ\ChannelInterface;

interface ConnectionInterface
{
    public function connect();
    public function getChannel(string $channelId = '') : ChannelInterface;
}
