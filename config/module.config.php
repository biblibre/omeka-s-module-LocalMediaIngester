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
    'csv_import' => [
        'media_ingester_adapter' => [
            'local' => CSVImport\MediaIngesterAdapter\LocalMediaIngesterAdapter::class,
        ],
    ],
    'form_elements' => [
        'invokables' => [
            'LocalMediaIngester\Form\ConfigForm' => Form\ConfigForm::class,
        ],
    ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => dirname(__DIR__) . '/language',
                'pattern' => '%s.mo',
                'text_domain' => null,
            ],
        ],
    ],
];
