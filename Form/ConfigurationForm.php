<?php

namespace ZipCode\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;
use ZipCode\ZipCode;

class ConfigurationForm extends BaseForm
{
    protected function buildForm()
    {
        $this->formBuilder->add(
            "geonames_username",
            TextType::class,
            [
                "data" => ZipCode::getConfigValue("geonames_username"),
                "label"=>Translator::getInstance()->trans("Geonames username", [], ZipCode::DOMAIN_NAME),
                "label_attr" => ["for" => "geonames_username"],
                "required" => true
            ]
        );
    }

    public static function getName()
    {
        return "zipcode_configuration_form";
    }
}
