<?php

namespace ZipCode\Hook;

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use ZipCode\ZipCode;

class FrontHook extends BaseHook
{
    public function getJsVar(HookRenderEvent $event)
    {
        $event->add(
            $this->render('zipcode-js.html', ['geonames_username' => ZipCode::getConfigValue('geonames_username')])
        );
    }
}
