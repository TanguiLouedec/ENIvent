<?php

namespace App\Controller;

use App\Form\CampusType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CampusController extends AbstractController
{
    #[Route('/campus', name: 'main_campus')]
    public function add(): Response
    {

        $campusForm = $this->createForm(CampusType::class);


        return $this->render('campus/addCampus.html.twig', [

            'campusForm' => $campusForm->createView()

        ]);
    }


}
