<?php

namespace App\Controller;



use App\Entity\City;
use App\Form\CityType;
use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends AbstractController
{
    #[Route('/city/add', name: 'add_city')]
    public function addCity(CityRepository $cityRepository, Request $request){

        $city = new City();
        $cityForm = $this->createForm(CityType::class,$city);
        $cityForm->handleRequest($request);

        if($cityForm->isSubmitted()&&$cityForm->isValid()) {
            $cityRepository->save($city, true);
            $this->addFlash('success', 'City Created !');
            return $this->redirectToRoute('list_city');
        }
        return $this->render('city/addCity.html.twig', ['cityForm' => $cityForm->createView()]);
    }

    #[Route('/city', name: 'list_city')]
    public function listCity(CityRepository $cityRepository): Response
    {
        //TODO city search bar

        $city = $cityRepository->findAll();

        return $this->render('city/listCity.html.twig', ['city' => $city]);
    }

    #[route('/city/delete/{id}', name: 'delete_city', requirements: ['id' =>'\d+'])]
    public function delete(int $id,CityRepository $cityRepository)
    {

        $city = $cityRepository->find($id);
        $cityRepository->remove($city, true);

        $this->addFlash('success', $city->getName() ." has been removed !");

        return $this-> redirectToRoute('list_city');

    }

    #[route('/city/update/{id}', name: 'city_update', requirements: ['id' =>'\d+'])]
    public function update(int $id,CityRepository $cityRepository, Request $request)
    {

        $city = $cityRepository->find($id);
        $cityForm = $this->createForm(CityType::class, $city);
        $cityForm->handleRequest($request);

        if($cityForm->isSubmitted()&&$cityForm->isValid()) {
            $cityRepository->save($city, true);
            $this->addFlash('success', 'City updated !');
            return $this->redirectToRoute('list_city');
        }
        return $this->render('city/update.html.twig',['cityForm' => $cityForm->createView()
        ]);


    }


}
