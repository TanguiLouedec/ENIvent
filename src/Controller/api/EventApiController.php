<?php

namespace App\Controller\api;

use App\Repository\CityRepository;
use App\Repository\EventRepository;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventApiController extends AbstractController
{
    #[Route('/event/api/locations/{cityId}', name: 'app_event_api', methods: 'GET')]
    public function getAll(
        LocationRepository $locationRepository,
        CityRepository $cityRepository,
        int $cityId,
    ): Response
    {
        $city = $cityRepository->find($cityId);
        $locations = $locationRepository->findBy(['city' => $city   ], ['id' => 'ASC']);
        //dd($locations);
        return $this->json($locations, 200, [],  ['groups' => 'city_data']);
    }
}
