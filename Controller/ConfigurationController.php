<?php

namespace ZipCode\Controller;

use ZipCode\ZipCode;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Translation\Translator;

class ConfigurationController extends BaseAdminController
{
    public function viewAction()
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], 'ZipCode', AccessManager::VIEW)) {
            return $response;
        }

        return $this->render("zipcode/configuration");
    }

    public function saveAction()
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], 'ZipCode', AccessManager::UPDATE)) {
            return $response;
        }

        $form = $this->createForm('zipcode_configuration_form');

        try {
            $data = $this->validateForm($form)->getData();

            $excludeData = [
                'success_url',
                'error_url',
                'error_message'
            ];

            foreach ($data as $key => $value) {
                if (!in_array($key, $excludeData)) {
                    ZipCode::setConfigValue($key, $value);
                }
            }
        } catch (\Exception $e) {
            $this->setupFormErrorContext(
                Translator::getInstance()->trans(
                    "Error",
                    [],
                    ZipCode::DOMAIN_NAME
                ),
                $e->getMessage(),
                $form
            );
            return $this->viewAction();
        }

        return $this->generateSuccessRedirect($form);
    }
}