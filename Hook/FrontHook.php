<?php

namespace ZipCode\Hook;

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use ZipCode\ZipCode;

class FrontHook extends BaseHook
{
    public static function getSubscribedHooks(): array
    {
        return [
            'main.after-javascript-include' => [
                ['type' => 'front', 'method' => 'getJsVar'],
            ],
        ];
    }

    public function getJsVar(HookRenderEvent $event): void
    {
        $event->add(
            $this->render('zipcode-js.html', ['geonames_username' => ZipCode::getConfigValue('geonames_username')])
        );
    }
}
