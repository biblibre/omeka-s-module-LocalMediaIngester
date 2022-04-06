# Local Media Ingester

Adds a media ingester for files already present on server, like
[FileSideload](https://github.com/omeka-s-modules/FileSideload), but allows to
define multiple paths.

Mostly useful for import scripts.

## Installation

See general end user documentation for [Installing a module](http://omeka.org/s/docs/user-manual/modules/#installing-modules)

## Configuration

In Omeka's `config/local.config.php`, add a section like this:

```php
<?php

return [
    /* ... */
    'local_media_ingester' => [
        'paths' => [
            '/data/upload1',
            '/data/upload2',
            /* ... */
        ],
    ],
];
```

Only files present in these directories will be allowed to be ingested.

In the module global settings (Admin > Modules > LocalMediaIngester's
"Configure" button) you can choose the default action on original files (keep
or delete).
This default action can be overriden when importing new media.

## Compatibility with other modules

* Local Media Ingester can be used as a media source for [CSVImport](https://github.com/omeka-s-modules/CSVImport)

## How to use in PHP code

You can use this ingester in PHP code like this

```php
$itemData['o:media'][] = [
    'o:ingester' => 'local',
    'ingest_filename' => '/path/to/file.png',
    'original_file_action' => 'keep', // or 'delete'
];
$api->create('items', $itemData);
```
