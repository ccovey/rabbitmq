<?php

namespace Ccovey\RabbitMQ\Connection;

class ConnectionParameters
{
    const DEFAULT_PORT = 5672;

    const DEFAULT_USER = 'guest';

    const DEFAULT_PASSWORD = 'guest';

    const LOGIN_METHOD = 'AMQPLAIN';

    const LOCALE = 'en_US';

    const CONNECTION_TIMEOUT = 3.0;

    const READ_WRITE_TIMEOUT = 3.0;

    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $vhost;

    /**
     * @var bool
     */
    private $insist;

    /**
     * @var string
     */
    private $loginMethod;

    private $loginResponse;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var int
     */
    private $connectionTimeout;

    /**
     * @var int
     */
    private $readWriteTimeout;

    /**
     * @var null
     */
    private $context;

    /**
     * @var bool
     */
    private $keepalive;

    /**
     * @var int
     */
    private $heartbeat;

    public function __construct(
        string $host,
        int $port = self::DEFAULT_PORT,
        string $user = self::DEFAULT_USER,
        string $password = self::DEFAULT_PASSWORD,
        string $vhost = '/',
        bool $insist = false,
        string $loginMethod = self::LOGIN_METHOD,
        $loginResponse = null,
        string $locale = self::LOCALE,
        float $connectionTimeout = self::CONNECTION_TIMEOUT,
        float $readWriteTimeout = self::READ_WRITE_TIMEOUT,
        $context = null,
        bool $keepalive = false,
        int $heartbeat = 0
    )
    {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
        $this->vhost = $vhost;
        $this->insist = $insist;
        $this->loginMethod = $loginMethod;
        $this->loginResponse = $loginResponse;
        $this->locale = $locale;
        $this->connectionTimeout = $connectionTimeout;
        $this->readWriteTimeout = $readWriteTimeout;
        $this->context = $context;
        $this->keepalive = $keepalive;
        $this->heartbeat = $heartbeat;
    }

    public function getHost() : string
    {
        return $this->host;
    }

    public function getPort() : int
    {
        return $this->port;
    }

    public function getUser() : string
    {
        return $this->user;
    }

    public function getPassword() : string
    {
        return $this->password;
    }

    public function getVhost() : string
    {
        return $this->vhost;
    }

    public function shouldInsist() : bool
    {
        return $this->insist;
    }

    public function getLoginMethod() : string
    {
        return $this->loginMethod;
    }

    public function getLoginResponse()
    {
        return $this->loginResponse;
    }

    public function getLocale() : string
    {
        return $this->locale;
    }

    public function getConnectionTimeout() : int
    {
        return $this->connectionTimeout;
    }

    public function getReadWriteTimeout() : int
    {
        return $this->readWriteTimeout;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function shouldKeepalive() : bool
    {
        return $this->keepalive;
    }

    public function getHeartbeat() : int
    {
        return $this->heartbeat;
    }
}
