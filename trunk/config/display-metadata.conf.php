<?php

use Trasweb\Plugins\DisplayMetadata\Model;
use Trasweb\Plugins\DisplayMetadata\Type;

return [
    'plugin' => [
        'dir' => __DIR__ . '/..',
        'name' => 'display-metadata',
    ],
    'views' => [
        'subpath' => '/views',
    ],
    'assets' => [
        'subpath' => '/assets',
    ],
    'default-metabox' => Type\None::class,
    'metabox-types' => [
        'post' => [
            'type' => Type\Post::class,
            'model' => Model\Post_Model::class,
        ],
        'tag_ID' => [
            'type' => Type\Term::class,
            'model' => Model\Term_Model::class,
        ],
        'user_id' => [
            'type' => Type\User::class,
            'model' => Model\User_Model::class,
        ],
        'c' => [
            'type' => Type\Comment::class,
            'model' => Model\Comment_Model::class,
        ],
    ]
];