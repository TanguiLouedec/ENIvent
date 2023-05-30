<?php

namespace App\Controller;

use App\Form\CampusType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CampusController extends AbstractController
{
    #[Route('/campus', name: 'add_campus')]
    public function addCampus(): Response
    {

        $campusForm = $this->createForm(CampusType::class);

        return $this->render('campus/addCampus.html.twig', [

            'campusForm' => $campusForm->createView()

        ]);
    }
    #[Route('/campus', name: 'show_campus')]
    public function showCampus(): Response
    {

        //TODO campus search bar



        return $this->render('campus/showCampus.html.twig', [



        ]);
    }

}
