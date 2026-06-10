<?php

namespace ZipCode\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;
use ZipCode\Form\ConfigurationForm;
use ZipCode\ZipCode;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Translation\Translator;

#[Route("/admin/module", name: "admin_zipcode")]
class ConfigurationController extends BaseAdminController
{
    #[Route("/zipcode/configuration", name: "_config_save", methods: ["POST"])]
    public function saveAction(): RedirectResponse
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
                'error_message',
                '_token',
            ];

            foreach ($data as $key => $value) {
                if (!in_array($key, $excludeData)) {
                    ZipCode::setConfigValue($key, $value);
                }
            }
        } catch (\Exception $e) {
            $this->getRequest()?->getSession()?->getFlashBag()->add(
                'error',
                Translator::getInstance()->trans('Error', [], ZipCode::DOMAIN_NAME).': '.$e->getMessage()
            );
        }

        return $this->generateRedirectFromRoute('admin.module.configure', [], ['module_code' => 'ZipCode']);
    }
}
