<?php

declare(strict_types=1);

namespace Imi\Kafka\Queue\Contract;

use Imi\Queue\Contract\IMessage;
use longlang\phpkafka\Consumer\ConsumeMessage;

interface IKafkaPopMessage extends IMessage
{
    public function getConsumeMessage(): ConsumeMessage;

    public function setConsumeMessage(ConsumeMessage $consumeMessage): void;
}
