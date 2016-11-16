<?php

namespace Ccovey\RabbitMQ;

use Ccovey\RabbitMQ\Consumer\Consumable;
use Ccovey\RabbitMQ\Producer\Publishable;
use PhpAmqpLib\Channel\AMQPChannel;

class Channel implements ChannelInterface
{
    /**
     * @var AMQPChannel
     */
    private $channel;

    public function __construct(AMQPChannel $channel)
    {
        $this->channel = $channel;
    }

    public function wait()
    {
        return $this->channel->wait();
    }

    public function acknowledge(string $deliveryTag, bool $multiple = false)
    {
        return $this->channel->basic_ack($deliveryTag, $multiple);
    }

    public function nack(string $deliveryTag, bool $multiple = false, bool $requeue = false)
    {
        return $this->channel->basic_nack($deliveryTag, $multiple, $requeue);
    }

    public function consume(Consumable $consumable) : ChannelInterface
    {
        $this->channel->basic_consume(
            $consumable->getQueueName(),
            $consumable->getConsumerTag(),
            $consumable->isNoLocal(),
            $consumable->isNoAck(),
            $consumable->isExclusive(),
            $consumable->isNoWait(),
            $consumable->getCallback(),
            $consumable->getTicket(),
            $consumable->getArguments()
        );

        return $this;
    }

    public function publish(Publishable $message) : ChannelInterface
    {
        if ($message->isDelayed()) {
            $queue = new Queue($message->getQueueName(), $message->getDelayedExchanged());
            $message->setExchange($message->getDelayedExchanged());
            $this->bindQueue($queue);
        }

        $this->channel->basic_publish(
            $message->getMessage(),
            $message->getExchange(),
            $message->getQueueName(),
            $message->isMandatory(),
            $message->isImmediate(),
            $message->getTicket()
        );

        return $this;
    }

    /**
     * @param     $prefetchSize
     * @param int $prefetchCount
     * @param     $aGlobal
     *
     * @return mixed
     */
    public function basicQos($prefetchSize = null, int $prefetchCount = 1, $aGlobal = null)
    {
        $this->channel->basic_qos($prefetchSize, $prefetchCount, $aGlobal);

        return $this;
    }

    /**
     * @param Queue $queue
     *
     * @return ChannelInterface
     */
    public function declareQueue(Queue $queue) : ChannelInterface
    {
        $this->channel->queue_declare(
            $queue->getQueueName(),
            $queue->isPassive(),
            $queue->isDurable(),
            $queue->isExclusive(),
            $queue->isAutoDelete(),
            $queue->isNoWait(),
            $queue->getArguments(),
            $queue->getTicket()
        );

        return $this;
    }

    public function bindQueue(Queue $queue) : ChannelInterface
    {
        $this->channel->queue_bind(
            $queue->getQueueName(),
            $queue->getExchange(),
            $queue->getQueueName(),
            $queue->isNoWait(),
            $queue->getArguments(),
            $queue->getTicket()
        );

        return $this;
    }

    public function declareExchange(Exchange $exchange) : ChannelInterface
    {
        $this->channel->exchange_declare(
            $exchange->getExchange(),
            $exchange->getType(),
            $exchange->isPassive(),
            $exchange->isDurable(),
            $exchange->isAutoDelete(),
            $exchange->isInternal(),
            $exchange->isNoWait(),
            $exchange->getArguments(),
            $exchange->getTicket()
        );

        return $this;
    }

    /**
     * @return array
     */
    public function getCallbacks() : array
    {
        return $this->channel->callbacks;
    }
}