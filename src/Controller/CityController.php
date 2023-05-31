<?php

namespace App\Controller;


use App\Form\CityType;
use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends AbstractController
{
    #[Route('/city/add', name: 'add_city')]
    public function addCity(): Response
    {

        $cityForm = $this->createForm(CityType::class);

        return $this->render('city/addCity.html.twig', [

            'cityForm' => $cityForm->createView()

            ]);
    }

    #[Route('/city', name: 'list_city')]
    public function listCity(CityRepository $cityRepository): Response
    {
        //TODO city search bar

        $city = $cityRepository->findAll();

        return $this->render('city/listCity.html.twig', [
            'city' => $city
        ]);
    }

    #[route('/delete/{id}', name: 'delete', requirements: ['id' =>'\d+'])]
    public function delete(int $id,CityRepository $cityRepository)
    {

        $city = $cityRepository->find($id);
        $cityRepository->remove($city, true);

        $this->addFlash('success', $city->getName() ." has been removed !");

        return $this-> redirectToRoute('list_city');

    }


}
