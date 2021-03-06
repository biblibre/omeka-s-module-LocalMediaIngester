<?php
namespace LocalMediaIngester\Media\Ingester;

use Omeka\Api\Request;
use Omeka\Entity\Media;
use Omeka\File\TempFileFactory;
use Omeka\File\Validator;
use Omeka\Media\Ingester\IngesterInterface;
use Omeka\Stdlib\ErrorStore;
use Zend\Form\Element\Text;
use Zend\View\Renderer\PhpRenderer;

class Local implements IngesterInterface
{
    /**
     * @var TempFileFactory
     */
    protected $tempFileFactory;

    /**
     * @var Validator
     */
    protected $validator;

    protected $config;

    public function __construct(TempFileFactory $tempFileFactory, Validator $validator, $config)
    {
        $this->tempFileFactory = $tempFileFactory;
        $this->validator = $validator;
        $this->config = $config;
    }

    public function getLabel()
    {
        return 'Local'; // @translate
    }

    public function getRenderer()
    {
        return 'file';
    }

    /**
     * Ingest from a URL.
     *
     * Accepts the following non-prefixed keys:
     *
     * + ingest_filename: (required) The filename to ingest.
     *
     * {@inheritDoc}
     */
    public function ingest(Media $media, Request $request, ErrorStore $errorStore)
    {
        $data = $request->getContent();
        if (!isset($data['ingest_filename'])) {
            $errorStore->addError('ingest_filename', 'No ingest filename specified'); // @translate;
            return;
        }

        $filepath = $data['ingest_filename'];
        $fileinfo = new \SplFileInfo($filepath);
        $realPath = $this->verifyFile($fileinfo);
        if (false === $realPath) {
            $errorStore->addError('ingest_filename', sprintf(
                'Cannot load file "%s". File does not exist or does not have sufficient permissions', // @translate
                $filepath
            ));
            return;
        }

        $tempFile = $this->tempFileFactory->build();
        $tempFile->setSourceName($data['ingest_filename']);

        // Copy the file to a temp path, so it is managed as a real temp file
        copy($realPath, $tempFile->getTempPath());

        if (!$this->validator->validate($tempFile, $errorStore)) {
            return;
        }

        if (!array_key_exists('o:source', $data)) {
            $media->setSource($data['ingest_filename']);
        }

        $storeOriginal = $data['store_original'] ?? true;
        $storeThumbnails = $data['store_thumbnails'] ?? true;
        $deleteTempFile = $data['delete_temp_file'] ?? true;
        $hydrateFileMetadataOnStoreOriginalFalse = $data['hydrate_file_metadata_on_store_original_false'] ?? false;

        $tempFile->mediaIngestFile($media, $request, $errorStore, $storeOriginal, $storeThumbnails, $deleteTempFile, $hydrateFileMetadataOnStoreOriginalFalse);
    }

    public function form(PhpRenderer $view, array $options = [])
    {
        $text = new Text('o:media[__index__][ingest_filename]');

        $text->setOptions([
            'label' => 'Path', // @translate
            'info' => 'File absolute path on the server', // @translate
        ]);

        $text->setAttributes([
            'id' => 'media-local-ingest-filename-__index__',
            'required' => true,
        ]);

        return $view->formRow($text);
    }

    public function verifyFile(\SplFileInfo $fileinfo)
    {
        $realPath = $fileinfo->getRealPath();
        if (false === $realPath) {
            return false;
        }

        if (!$fileinfo->isFile() || !$fileinfo->isReadable()) {
            return false;
        }

        $dirname = $fileinfo->getPath();
        $paths = $this->config['paths'] ?? [];
        $paths = array_filter($paths, function ($path) use ($dirname) {
            return 0 === strpos($dirname, $path);
        });
        if (empty($paths)) {
            return false;
        }

        return $realPath;
    }
}

