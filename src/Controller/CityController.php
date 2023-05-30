<?php

namespace App\Controller;

use App\Form\CampusType;
use App\Form\CityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends AbstractController
{
    #[Route('/city', name: 'app_city')]
    public function index(): Response
    {

        $cityForm = $this->createForm(CityType::class);

        return $this->render('city/addCity.html.twig', [

            'cityForm' => $cityForm->createView()

            ]);
    }
}
