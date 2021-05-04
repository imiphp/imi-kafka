<?php

namespace ImiApp\Kafka\QueueTest;

use Imi\Bean\Annotation\Bean;
use Imi\Queue\Contract\IMessage;
use Imi\Queue\Driver\IQueueDriver;
use Imi\Queue\Service\BaseQueueConsumer;
use Imi\Redis\Redis;

/**
 * @Bean("QueueTestConsumer")
 */
class QueueTestConsumer extends BaseQueueConsumer
{
    /**
     * 处理消费.
     *
     * @param IMessage                       $message
     * @param \Imi\Queue\Driver\IQueueDriver $queue
     *
     * @return void
     */
    protected function consume(IMessage $message, IQueueDriver $queue)
    {
        var_dump(__CLASS__, $message->getMessage());
        $queueTestMessage = QueueTestMessage::fromMessage($message->getMessage());
        Redis::set('imi-kafka:consume:QueueTest:' . $queueTestMessage->getMemberId(), $message->getMessage());

        $queue->success($message);
    }
}
