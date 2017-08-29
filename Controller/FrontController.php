<?php

namespace ZipCode\Controller;

use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Model\CountryQuery;

class FrontController extends BaseFrontController
{
    public function getCountryIsoAlpha2FromId($id)
    {
        $country = CountryQuery::create()
            ->findPk($id);

        if (null === $country) {
            return JsonResponse::create(null, 404);
        }

        return JsonResponse::create(['isoalpha2'=>$country->getIsoalpha2()]);
    }
}
