<?php

namespace ZipCode\Controller;

use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Model\CountryQuery;
use Symfony\Component\Routing\Annotation\Route;
use ZipCode\ZipCode;

/**
 * @Route("/zipcode", name="front_zipcode")
 */
class FrontController extends BaseFrontController
{
    /**
     * @Route("/isoalpha2/{id}", name="_isolapha2", methods="GET")
     */
    public function getCountryIsoAlpha2FromId($id)
    {
        $country = CountryQuery::create()
            ->findPk($id);

        if (null === $country) {
            return new JsonResponse(null, 404);
        }

        return new JsonResponse(['isoalpha2'=>$country->getIsoalpha2()]);
    }

    /**
     * @Route("/query", name="_query", methods="GET")
     */
    public function query(Request $request)
    {
        $searchMode = $request->get('mode', 'postalcode');
        $searchTerm = $request->get('q', '');

        $data = [
            'country' => $request->get('country', 'fr'),
            'maxRows' => (int) $request->get('maxRows', 10),
            $searchMode => $searchTerm,
            'username' => ZipCode::getConfigValue('geonames_username'),
        ];

        $baseUrl = 'https://secure.geonames.org/postalCodeSearchJSON?' . http_build_query($data);

        return JsonResponse::fromJsonString(file_get_contents($baseUrl));
    }
}
