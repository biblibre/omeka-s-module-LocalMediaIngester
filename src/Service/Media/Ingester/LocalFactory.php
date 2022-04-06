<?php
namespace LocalMediaIngester\Service\Media\Ingester;

use LocalMediaIngester\Media\Ingester\Local;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class LocalFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $services, $requestedName, array $options = null)
    {
        $tempFileFactory = $services->get('Omeka\File\TempFileFactory');
        $validator = $services->get('Omeka\File\Validator');
        $config = $services->get('Config');
        $settings = $services->get('Omeka\Settings');
        $logger = $services->get('Omeka\Logger');

        return new Local($tempFileFactory, $validator, $config['local_media_ingester'], $settings, $logger);
    }
}
