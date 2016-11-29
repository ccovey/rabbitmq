<?php

namespace Ccovey\RabbitMQ;

use Ccovey\RabbitMQ\Consumer\Consumable;
use Ccovey\RabbitMQ\Producer\Publishable;
use PhpAmqpLib\Message\AMQPMessage;

interface ChannelInterface
{
    public function wait();
    public function acknowledge(string $deliveryTag, bool $multiple = false);
    public function nack(string $deliveryTag, bool $multiple = false);
    public function consume(Consumable $consumable) : ChannelInterface;
    public function publish(Publishable $message) : ChannelInterface;
    public function basicQos($prefetchSize = null, int $prefetchCount, $aGlobal = null);
    public function declareQueue(Queue $queue) : ChannelInterface;
    public function bindQueue(Queue $queue) : ChannelInterface;
    public function declareExchange(Exchange $exchange) : ChannelInterface;
    public function getCallbacks() : array;
    public function setCallbacks(array $callbacks = []);
    public function getMessage(Consumable $consumable);
    public function getQueueSize(Queue $queue) : int;
}
