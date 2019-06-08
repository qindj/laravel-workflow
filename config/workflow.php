<?php

return [
    'post_lifecycle'   => [
        'type' => 'state_machine',
        'marking_store' => [
            'type' => 'single_state',
        ],
        'supports' => ['App\Post'],
        'places' => [
            'draft',
            'review',
            'rejected',
            'published'
        ],
        'transitions' => [
            'to_review' => [
                'from' => 'draft',
                'to' => 'review',
            ],
            'publish' => [
                'from' => 'review',
                'to' => 'published',
            ],
            'reject' => [
                'from' => 'review',
                'to' => 'rejected',
            ]
        ],
    ]
];
