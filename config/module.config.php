<?php

namespace LocalMediaIngester;

return [
    'media_ingesters' => [
        'factories' => [
            'local' => Service\Media\Ingester\LocalFactory::class,
        ],
    ],
    'local_media_ingester' => [
        'paths' => [
            // '/data/files1',
            // '/data/files2',
        ],
    ],
];
