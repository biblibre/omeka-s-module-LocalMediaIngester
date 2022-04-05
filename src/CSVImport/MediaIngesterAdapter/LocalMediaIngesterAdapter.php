<?php

namespace LocalMediaIngester\CSVImport\MediaIngesterAdapter;

use CSVImport\MediaIngesterAdapter\MediaIngesterAdapterInterface;

class LocalMediaIngesterAdapter implements MediaIngesterAdapterInterface
{
    public function getJson($mediaDatum)
    {
        $mediaDatumJson = [
            'ingest_filename' => $mediaDatum,
        ];

        return $mediaDatumJson;
    }
}
