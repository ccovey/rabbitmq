<?php

namespace Ccovey\RabbitMQ\Producer;

use PhpAmqpLib\Message\AMQPMessage;

interface Publishable
{
    /**
     * @return AMQPMessage
     */
    public function getMessage() : AMQPMessage;

    /**
     * @return bool
     */
    public function isDelayed() : bool;

    /**
     * @return array
     */
    public function getBody() : array;

    /**
     * @return string
     */
    public function getQueueName() : string;

    /**
     * @return string
     */
    public function getExchange() : string;

    /**
     * @return bool
     */
    public function isMandatory() : bool;

    /**
     * @return bool
     */
    public function isImmediate() : bool;

    /**
     * @return int
     */
    public function getTicket();

    public function getDelayedExchanged() : string;
}
