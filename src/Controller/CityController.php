<?php

namespace App\Controller;

use App\Form\CampusType;
use App\Form\CityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends AbstractController
{
    #[Route('/city', name: 'add_city')]
    public function addCity(): Response
    {

        $cityForm = $this->createForm(CityType::class);

        return $this->render('city/addCity.html.twig', [

            'cityForm' => $cityForm->createView()

            ]);
    }
    #[Route('/city', name: 'show_city')]
    public function showCity(): Response
    {



        return $this->render('city/showCity.html.twig', [



        ]);
    }
}
