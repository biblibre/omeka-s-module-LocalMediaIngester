<?php

namespace LocalMediaIngester;

return [
    'media_ingesters' => [
        'factories' => [
            'local' => Service\Media\Ingester\LocalFactory::class,
        ],
    ],
];
