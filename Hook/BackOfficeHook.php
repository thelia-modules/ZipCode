<?php

namespace ZipCode\Hook;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Form\TheliaFormFactory;
use Thelia\Core\Hook\BaseHook;
use Thelia\Core\Template\Parser\ParserResolver;
use ZipCode\Form\ConfigurationForm;
use ZipCode\ZipCode;

class BackOfficeHook extends BaseHook
{
    public function __construct(
        private readonly TheliaFormFactory $formFactory,
        ?EventDispatcherInterface $dispatcher = null,
        ?ParserResolver $parserResolver = null,
    ) {
        parent::__construct($dispatcher, $parserResolver);
    }

    public static function getSubscribedHooks(): array
    {
        return [
            'module.configuration' => [
                ['type' => 'back', 'method' => 'onModuleConfiguration'],
            ],
        ];
    }

    public function onModuleConfiguration(HookRenderEvent $event): void
    {
        $form = $this->formFactory->createForm(ConfigurationForm::getName(), data: [
            'geonames_username' => ZipCode::getConfigValue('geonames_username', ''),
        ]);

        $event->add($this->render('ZipCode/module-configuration.html.twig', [
            'form' => $form->createView()->getView(),
        ]));
    }
}
