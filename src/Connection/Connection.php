<?php

namespace Ccovey\RabbitMQ\Connection;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class Connection implements ConnectionInterface
{
    private $stream;

    public function __construct(ConnectionParameters $parameters)
    {
        $this->stream = new AMQPStreamConnection(
            $parameters->getHost(),
            $parameters->getPort(),
            $parameters->getUser(),
            $parameters->getPassword(),
            $parameters->getVhost(),
            $parameters->shouldInsist(),
            $parameters->getLoginMethod(),
            $parameters->getLoginResponse(),
            $parameters->getLocale(),
            $parameters->getConnectionTimeout(),
            $parameters->getReadWriteTimeout(),
            $parameters->getContext(),
            $parameters->shouldKeepalive(),
            $parameters->getHeartbeat()
        );
    }

    public function connect()
    {
        $this->stream->reconnect();
    }

    public function getChannel(string $channelId)
    {
        return new Channel($this->stream->channel($channelId));
    }
}
