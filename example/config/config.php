<?php

defined('KAFKA_BOOTSTRAP_SERVERS') || define('KAFKA_BOOTSTRAP_SERVERS', imiGetEnv('KAFKA_BOOTSTRAP_SERVERS', '127.0.0.1:9092'));

return [
    // 项目根命名空间
    'namespace'    => 'KafkaApp',

    // 配置文件
    'configs'    => [
        'beans'        => __DIR__ . '/beans.php',
    ],

    // 扫描目录
    'beanScan'    => [
        'KafkaApp\Listener',
        'KafkaApp\Task',
        'KafkaApp\Consumer',
        'KafkaApp\Kafka',
        'KafkaApp\Process',
    ],

    // 组件命名空间
    'components'    => [
        'Kafka'  => 'Imi\Kafka',
    ],

    // 主服务器配置
    'mainServer'    => [
        'namespace'    => 'KafkaApp\ApiServer',
        'type'         => Imi\Server\Type::HTTP,
        'host'         => '127.0.0.1',
        'port'         => 8080,
        'configs'      => [
            'worker_num'        => 1,
        ],
    ],

    // 子服务器（端口监听）配置
    'subServers'        => [
    ],

    // 连接池配置
    'pools'    => [
        'redis'    => [
            'sync'    => [
                'pool'    => [
                    'class'        => \Imi\Redis\SyncRedisPool::class,
                    'config'       => [
                        'maxResources'    => 10,
                        'minResources'    => 0,
                    ],
                ],
                'resource'    => [
                    'host'      => imiGetEnv('REDIS_SERVER_HOST', '127.0.0.1'),
                    'port'      => 6379,
                    'password'  => null,
                ],
            ],
            'async'    => [
                'pool'    => [
                    'class'        => \Imi\Redis\CoroutineRedisPool::class,
                    'config'       => [
                        'maxResources'    => 10,
                        'minResources'    => 1,
                    ],
                ],
                'resource'    => [
                    'host'      => imiGetEnv('REDIS_SERVER_HOST', '127.0.0.1'),
                    'port'      => 6379,
                    'password'  => null,
                ],
            ],
        ],
        'kafka'    => [
            'sync'    => [
                'pool'    => [
                    'class'        => \Imi\Kafka\Pool\KafkaSyncPool::class,
                    'config'       => [
                        'maxResources'    => 10,
                        'minResources'    => 0,
                    ],
                ],
                'resource'    => [
                    'bootstrapServers' => KAFKA_BOOTSTRAP_SERVERS,
                    'groupId'          => 'test',
                ],
            ],
            'async'    => [
                'pool'    => [
                    'class'        => \Imi\Kafka\Pool\KafkaCoroutinePool::class,
                    'config'       => [
                        'maxResources'    => 10,
                        'minResources'    => 1,
                    ],
                ],
                'resource'    => [
                    'bootstrapServers' => KAFKA_BOOTSTRAP_SERVERS,
                    'groupId'          => 'test',
                ],
            ],
        ],
    ],

    // redis 配置
    'redis' => [
        // 数默认连接池名
        'defaultPool'   => 'redis',
    ],
];
