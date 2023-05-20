<?php

declare(strict_types=1);

use Imi\Util\Imi;

use function Imi\env;

\defined('KAFKA_BOOTSTRAP_SERVERS') || \define('KAFKA_BOOTSTRAP_SERVERS', env('KAFKA_BOOTSTRAP_SERVERS', '127.0.0.1:9092'));

return [
    // 项目根命名空间
    'namespace'         => 'KafkaApp',

    // 配置文件
    'configs'           => [
        'beans'        => __DIR__ . '/beans.php',
    ],

    // 扫描目录
    // 'beanScan'    => [
    //     'KafkaApp\Listener',
    //     'KafkaApp\Task',
    //     'KafkaApp\Consumer',
    //     'KafkaApp\Kafka',
    //     'KafkaApp\Process',
    // ],

    // 组件命名空间
    'components'        => [
        'Swoole'    => 'Imi\Swoole',
        'Workerman' => 'Imi\Workerman',
        'Kafka'     => 'Imi\Kafka',
    ],

    // 主服务器配置
    'mainServer'        => [
        'namespace'    => 'KafkaApp\ApiServer',
        'type'         => \Imi\Swoole\Server\Type::HTTP,
        'host'         => '127.0.0.1',
        'port'         => 8080,
        'configs'      => [
            'worker_num'        => 1,
            'max_wait_time'     => 30,
        ],
    ],

    // Workerman 服务器配置
    'workermanServer'   => [
        'http' => [
            'namespace'    => 'KafkaApp\ApiServer',
            'type'         => \Imi\Workerman\Server\Type::HTTP,
            'host'         => '127.0.0.1',
            'port'         => 8080,
            'configs'      => [
            ],
        ],
    ],

    'workerman'       => [
        'worker' => [
            'stopTimeout' => 30,
        ],
    ],

    // 子服务器（端口监听）配置
    'subServers'        => [
    ],

    // 连接池配置
    'pools'             => Imi::checkAppType('swoole') ? [
        'redis'    => [
            'pool'        => [
                'class'        => \Imi\Swoole\Redis\Pool\CoroutineRedisPool::class,
                'config'       => [
                    'maxResources'    => 10,
                    'minResources'    => 1,
                ],
            ],
            'resource'    => [
                'host'      => env('REDIS_SERVER_HOST', '127.0.0.1'),
                'port'      => 6379,
                'password'  => null,
            ],
        ],
        'kafka'    => [
            'pool'        => [
                'class'        => \Imi\Kafka\Pool\KafkaCoroutinePool::class,
                'config'       => [
                    'maxResources'    => 10,
                    'minResources'    => 1,
                ],
            ],
            'resource'    => [
                'bootstrapServers' => KAFKA_BOOTSTRAP_SERVERS,
                'groupId'          => 'test1',
            ],
        ],
    ] : [],

    // redis 配置
    'redis'             => [
        // 数默认连接池名
        'defaultPool'   => 'redis',
        'connections'   => [
            'redis' => [
                'host'      => env('REDIS_SERVER_HOST', '127.0.0.1'),
                'port'      => 6379,
                'password'  => null,
            ],
        ],
    ],
    'kafka'             => [
        'connections' => [
            'kafka'    => [
                'bootstrapServers' => KAFKA_BOOTSTRAP_SERVERS,
                'groupId'          => 'test2',
            ],
        ],
    ],
    // 日志配置
    'logger'            => [
        'channels' => [
            'imi' => [
                'handlers' => [
                    [
                        'class'     => \Imi\Log\Handler\ConsoleHandler::class,
                        'formatter' => [
                            'class'     => \Imi\Log\Formatter\ConsoleLineFormatter::class,
                            'construct' => [
                                'format'                     => null,
                                'dateFormat'                 => 'Y-m-d H:i:s',
                                'allowInlineLineBreaks'      => true,
                                'ignoreEmptyContextAndExtra' => true,
                            ],
                        ],
                    ],
                    [
                        'class'     => \Monolog\Handler\RotatingFileHandler::class,
                        'construct' => [
                            'filename' => \dirname(__DIR__) . '/.runtime/logs/log.log',
                        ],
                        'formatter' => [
                            'class'     => \Monolog\Formatter\LineFormatter::class,
                            'construct' => [
                                'dateFormat'                 => 'Y-m-d H:i:s',
                                'allowInlineLineBreaks'      => true,
                                'ignoreEmptyContextAndExtra' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
