<?php

namespace ZipCode\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use ZipCode\Form\ConfigurationForm;
use ZipCode\ZipCode;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Translation\Translator;

#[Route("/admin/module", name:  "admin_zipcode")]
class ConfigurationController extends BaseAdminController
{
    #[Route("/ZipCode", name:  "_config", methods: "GET")]
    public function viewAction(): ?Response
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], 'ZipCode', AccessManager::VIEW)) {
            return $response;
        }

        return $this->render("zipcode/configuration");
    }

    #[Route("/zipcode/configuration", name:  "_config_save", methods: "POST")]
    public function saveAction(): RedirectResponse|Response|null
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], 'ZipCode', AccessManager::UPDATE)) {
            return $response;
        }

        $form = $this->createForm(ConfigurationForm::getName());

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
