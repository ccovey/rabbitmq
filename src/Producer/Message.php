<?php

namespace Ccovey\RabbitMQ\Producer;

use DateTime;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class Message implements Publishable
{
    /**
     * @var array
     */
    private $body;

    /**
     * @var string
     */
    private $queueName;

    /**
     * @var AMQPMessage
     */
    private $message;

    /**
     * @var string
     */
    private $exchange;

    /**
     * @var bool
     */
    private $mandatory;

    /**
     * @var bool
     */
    private $immediate;

    /**
     * @var int
     */
    private $ticket;

    /**
     * @var array
     */
    private $properties;

    /**
     * @var int
     */
    private $deliveryMode;

    /**
     * @var string
     */
    private $delayedExchange;

    public function __construct(
        array $body,
        string $queueName,
        string $exchange = '',
        bool $mandatory = false,
        bool $immediate = false,
        int $ticket = null,
        array $properties = [],
        int $deliveryMode = AMQPMessage::DELIVERY_MODE_PERSISTENT
    ) {
        $this->body = $body;
        $this->queueName = $queueName;
        $this->exchange = $exchange;
        $this->mandatory = $mandatory;
        $this->immediate = $immediate;
        $this->ticket = $ticket;
        $this->deliveryMode = $deliveryMode;
        $this->properties = $this->buildProperties($properties);

        $this->message = new AMQPMessage(json_encode($body), $this->properties);
    }

    /**
     * @return bool
     */
    public function isDelayed() : bool
    {
        $now = new \DateTime();

        return isset($this->body['scheduledAt'])
               && $this->body['scheduledAt'] instanceof DateTime
               && $now < $this->body['scheduledAt'];
    }

    public function getScheduledAtInSeconds() : int
    {
        $now = new \DateTime();

        return $this->body['scheduledAt']->getTimestamp() - $now->getTimestamp() * 1000;
    }

    public function getMessage() : AMQPMessage
    {
        return $this->message;
    }

    private function buildProperties(array $properties) : array
    {
        // I don't love this now that it is out of OpenSky. May need to be smarter.
        if ($this->isDelayed()) {
            if (!isset($properties['application_headers'])) {
                $properties['application_headers'] = new AMQPTable(['x-delay' => $this->getScheduledAtInSeconds()]);
            } else {
                $properties['application_headers']->set('x-delay', $this->getScheduledAtInSeconds());
            }
        }

        $properties['delivery_mode'] = $this->deliveryMode;

        return $properties;
    }

    public function getBody() : array
    {
        return $this->body;
    }

    public function getQueueName() : string
    {
        return $this->queueName;
    }

    public function getExchange() : string
    {
        return $this->exchange;
    }

    public function isMandatory() : bool
    {
        return $this->mandatory;
    }

    public function isImmediate() : bool
    {
        return $this->immediate;
    }

    public function getTicket()
    {
        return $this->ticket;
    }

    public function getProperties() : array
    {
        return $this->properties;
    }

    public function getDeliveryMode() : int
    {
        return $this->deliveryMode;
    }

    /**
     * @param int $deliveryMode
     */
    public function setDeliveryMode(int $deliveryMode)
    {
        $this->deliveryMode = $deliveryMode;
        $this->properties['delivery_mode'] = $this->deliveryMode;
    }

    public function getDelayedExchanged() : string
    {
        return $this->delayedExchange;
    }
}
