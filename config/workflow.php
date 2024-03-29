<?php

return [
    'blog_publishing'   => [
        'type' => 'workflow',
        'audit_trail' => [
            'enabled' => true
        ],
        'marking_store' => [
            'type' => 'single_state', # or 'multiple_state'
            'arguments' => [
                'currentPlace'
            ]
        ],
        'supports' => ['App\Post'],
        'initial_place' => 'draft',
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
