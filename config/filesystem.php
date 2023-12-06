<?php

return [
    // 默认磁盘
    'default' => 'local',
    // 磁盘列表
    'disks'   => [
        'local'  => [
            'type' => 'local',
            'root' => app()->getRuntimePath() . 'storage',
        ],
        'public' => [
            // 磁盘类型
            'type'       => 'local',
            // 磁盘路径
            'root'       => app()->getRootPath() . 'public/upload/article/img',
            // 磁盘路径对应的外部URL路径
            'url'        => '/upload/article/img',
            // 可见性
            'visibility' => 'public',
        ],
        'img_user' => [
            // 磁盘类型
            'type'       => 'local',
            // 磁盘路径
            'root'       => app()->getRootPath() . 'public/upload/img_user',
            // 磁盘路径对应的外部URL路径
            'url'        => '/upload/img_user',
            // 可见性
            'visibility' => 'public',
        ],
        'link_img' => [
            // 磁盘类型
            'type'       => 'local',
            // 磁盘路径
            'root'       => app()->getRootPath() . 'public/upload/link_img',
            // 磁盘路径对应的外部URL路径
            'url'        => '/upload/link_img',
            // 可见性
            'visibility' => 'public',
        ],
        // 更多的磁盘配置信息
    ],
];
