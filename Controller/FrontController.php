<?php

namespace ZipCode\Controller;

use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Model\CountryQuery;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/zipcode/isoalpha2", name="front_zipcode")
 */
class FrontController extends BaseFrontController
{
    /**
     * @Route("/{id}", name="_isolapha2", methods="GET")
     */
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
