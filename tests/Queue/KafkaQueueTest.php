<?php

declare(strict_types=1);

namespace Imi\Kafka\Test\Queue;

use Imi\App;
use Imi\Queue\Driver\IQueueDriver;

class KafkaQueueTest extends BaseQueueTestCase
{
    protected function getDriver(): IQueueDriver
    {
        // @phpstan-ignore-next-line
        return App::getBean('KafkaQueueDriver', 'imi-kafka-queue-test', [
            'poolName' => 'kafka',
            'groupId'  => 'queue-test',
        ]);
    }
}
