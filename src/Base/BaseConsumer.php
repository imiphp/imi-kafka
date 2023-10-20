<?php

declare(strict_types=1);

namespace Imi\Kafka\Base;

use Imi\Bean\Annotation\AnnotationManager;
use Imi\Bean\BeanFactory;
use Imi\Kafka\Annotation\Consumer as ConsumerAnnotation;
use Imi\Kafka\Contract\IConsumer;
use Imi\Kafka\Pool\KafkaPool;
use Imi\Util\Imi;
use longlang\phpkafka\Consumer\ConsumeMessage;
use longlang\phpkafka\Consumer\Consumer;

use function Yurun\Swoole\Coroutine\goWait;

/**
 * 消费者基类.
 */
abstract class BaseConsumer implements IConsumer
{
    protected ConsumerAnnotation $consumerAnnotation;

    protected ?Consumer $consumer = null;

    protected bool $running = false;

    public function __construct()
    {
        $this->initConfig();
    }

    /**
     * {@inheritDoc}
     */
    protected function initConfig(): void
    {
        $class = BeanFactory::getObjectClass($this);
        // @phpstan-ignore-next-line
        $this->consumerAnnotation = AnnotationManager::getClassAnnotations($class, ConsumerAnnotation::class, true, true);
    }

    /**
     * {@inheritDoc}
     */
    public function run(): void
    {
        $consumerAnnotation = $this->consumerAnnotation;
        $config = [];
        if (null !== $consumerAnnotation->groupId)
        {
            $config['groupId'] = $consumerAnnotation->groupId;
        }
        $consumer = $this->consumer = KafkaPool::createConsumer($consumerAnnotation->poolName, $consumerAnnotation->topic, $config);
        $this->running = true;
        $isSwoole = Imi::checkAppType('swoole');
        while ($this->running)
        {
            $message = $consumer->consume();
            if ($message)
            {
                if ($isSwoole)
                {
                    goWait(function () use ($message, $consumer): void {
                        $this->consume($message);
                        $consumer->ack($message);
                    }, -1, true);
                }
                else
                {
                    $this->consume($message);
                    $consumer->ack($message);
                }
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function stop(): void
    {
        $this->running = false;
    }

    /**
     * {@inheritDoc}
     */
    public function close(): void
    {
        $this->consumer->close();
        $this->consumer = null;
    }

    /**
     * 消费任务
     */
    abstract protected function consume(ConsumeMessage $message): void;
}
