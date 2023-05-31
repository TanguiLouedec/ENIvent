<?php

namespace App\Controller;

use App\Form\CampusType;
use App\Repository\CampusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CampusController extends AbstractController
{
    #[Route('/campus/add', name: 'add_campus')]
    public function addCampus(): Response
    {

        $campusForm = $this->createForm(CampusType::class);

        return $this->render('campus/addCampus.html.twig', [

            'campusForm' => $campusForm->createView()

        ]);
    }
    #[Route('/campus', name: 'list_campus')]
    public function listCampus(CampusRepository $campusRepository): Response
    {
        //TODO campus search bar

        $campus = $campusRepository->findAll();

        return $this->render('campus/listCampus.html.twig', [
            'campus' => $campus
        ]);
    }

    #[route('/delete/{id}', name: 'delete', requirements: ['id' =>'\d+'])]
    public function delete(int $id,CampusRepository $campusRepository)
    {

        $campus = $campusRepository->find($id);
        $campusRepository->remove($campus, true);

        $this->addFlash('success', $campus->getName() ." has been removed !");

        return $this-> redirectToRoute('list_campus');

    }



}
