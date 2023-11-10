<?php

declare(strict_types=1);

namespace Imi\Kafka\Pool;

use longlang\phpkafka\Consumer\Consumer;

trait TKafkaPool
{
    /**
     * 使用连接池配置创建消费者.
     */
    public function createConsumer(string|array|null $topic = null, array $config = []): Consumer
    {
        $resourceConfig = $this->getNextResourceConfig();
        $config = KafkaPool::createConsumerConfig(array_merge($resourceConfig, $config));
        if ($topic)
        {
            $config->setTopic($topic);
        }

        return new Consumer($config);
    }
}
