<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocationController extends AbstractController
{
    #[Route('/add', name:'add')]
    public function add(LocationRepository $locationRepository, Request $request){

        $location = new Location();
        $locationForm = $this->createForm(LocationType::class,$location);
        $locationForm->handleRequest($request);

        if($locationForm->isSubmitted()&&$locationForm->isValid()){
            $locationRepository->save($location, true);
            $this->addFlash('success','Location successfully added !');
            return $this->redirectToRoute('event_add');
        }
        return $this->render('location/add.html.twig', ['eventForm' => $locationForm->createView()]);
    }
}
