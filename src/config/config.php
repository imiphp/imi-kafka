<?php

return [
    'beanScan'  => [
        'Imi\Kafka\Annotation',
        'Imi\Kafka\Pool',
        'Imi\Kafka\Queue',
    ],
    // 组件命名空间
    'components'    => [
        'Queue'  => 'Imi\Queue',
    ],
];
