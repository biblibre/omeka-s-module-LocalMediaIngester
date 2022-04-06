<?php
namespace LocalMediaIngester;

use Omeka\Module\AbstractModule;

class Module extends AbstractModule
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getConfigForm($renderer)
    {
        $formElementManager = $this->getServiceLocator()->get('FormElementManager');
        $settings = $this->getServiceLocator()->get('Omeka\Settings');

        $form = $formElementManager->get('LocalMediaIngester\Form\ConfigForm');
        $form->setData([
            'original_file_action' => $settings->get('localmediaingester_original_file_action', 'keep'),
        ]);

        return $renderer->formCollection($form, false);
    }

    public function handleConfigForm($controller)
    {
        $formElementManager = $this->getServiceLocator()->get('FormElementManager');
        $settings = $this->getServiceLocator()->get('Omeka\Settings');

        $form = $formElementManager->get('LocalMediaIngester\Form\ConfigForm');
        $form->setData($controller->params()->fromPost());
        if (!$form->isValid()) {
            $controller->messenger()->addErrors($form->getMessages());
            return false;
        }

        $formData = $form->getData();
        $settings->set('localmediaingester_original_file_action', $formData['original_file_action']);

        return true;
    }
}
