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
