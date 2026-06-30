<?php

namespace ZipCode\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Model\CountryQuery;
use ZipCode\ZipCode;

#[Route("/zipcode", name:  "front_zipcode")]
class FrontController extends BaseFrontController
{
    #[Route("/isoalpha2/{id}", name:  "_isolapha2", methods: "GET")]
    public function getCountryIsoAlpha2FromId($id): JsonResponse
    {
        $country = CountryQuery::create()
            ->findPk($id);

        if (null === $country) {
            return new JsonResponse(null, 404);
        }

        return new JsonResponse(['isoalpha2'=>$country->getIsoalpha2()]);
    }

    #[Route("/query", name:  "_query", methods: "GET")]
    public function query(Request $request): JsonResponse
    {
        $searchMode = $request->attributes->get('mode', $request->query->get('mode', $request->request->get('mode', 'postalcode')));
        $searchTerm = $request->attributes->get('q', $request->query->get('q', $request->request->get('q', '')));

        $data = [
            'country' => $request->attributes->get('country', $request->query->get('country', $request->request->get('country', 'fr'))),
            'maxRows' => (int) $request->attributes->get('maxRows', $request->query->get('maxRows', $request->request->get('maxRows', 10))),
            $searchMode => $searchTerm,
            'username' => ZipCode::getConfigValue('geonames_username'),
        ];

        $baseUrl = 'https://secure.geonames.org/postalCodeSearchJSON?' . http_build_query($data);

        return JsonResponse::fromJsonString(file_get_contents($baseUrl));
    }
}
