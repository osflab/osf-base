<?php

return [
    'admin' => [
        'admin@test.com',
        'otheradmin@test.com'
    ],
    'controller' => [
        'common' => 'PUBLIC',
        'login' => 'NOTLOGGED',
        'logout' => 'LOGGED',
        'product' => '',
        'info' => '',
        'admin' => 'ADMIN'
    ],
    'action' => [
        'common' => [
            'index' => ''
        ],
        'login' => [
            'index' => '',
            'register' => ''
        ],
        'logout' => [
            'index' => ''
        ],
        'product' => [
            'add' => 'LOGGED',
            'remove' => 'LOGGED'
        ],
        'info' => [
            'public' => 'PUBLIC',
            'private' => [
                'reader@test.com',
                'ADMIN'
            ]
        ],
        'admin' => [
            'params' => ''
        ]
    ]
];
