<?php

namespace Ccovey\RabbitMQ\Connection;

use Ccovey\RabbitMQ\Channel;
use Ccovey\RabbitMQ\ChannelInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class Connection implements ConnectionInterface
{
    /**
     * @var ConnectionParameters
     */
    private $parameters;

    /**
     * @var AMQPStreamConnection
     */
    private $stream;

    public function __construct(ConnectionParameters $parameters)
    {
        $this->parameters = $parameters;
    }

    public function connect()
    {
        $this->getStream()->reconnect();
    }

    public function getChannel(string $channelId = '') : ChannelInterface
    {
        return new Channel($this->getStream()->channel($channelId));
    }

    protected function getStream()
    {
        if (!$this->stream) {
            $this->stream = new AMQPStreamConnection(
                $this->parameters->getHost(),
                $this->parameters->getPort(),
                $this->parameters->getUser(),
                $this->parameters->getPassword(),
                $this->parameters->getVhost(),
                $this->parameters->shouldInsist(),
                $this->parameters->getLoginMethod(),
                $this->parameters->getLoginResponse(),
                $this->parameters->getLocale(),
                $this->parameters->getConnectionTimeout(),
                $this->parameters->getReadWriteTimeout(),
                $this->parameters->getContext(),
                $this->parameters->shouldKeepalive(),
                $this->parameters->getHeartbeat()
            );
        }

        return $this->stream;
    }
}
