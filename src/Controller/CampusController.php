<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\CampusType;
use App\Repository\CampusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CampusController extends AbstractController
{
    #[Route('/campus/add', name: 'add_campus')]
    public function addCampus(CampusRepository $campusRepository, Request $request): Response{

        $campus = new Campus();
        $campusForm = $this->createForm(CampusType::class, $campus);
        $campusForm->handleRequest($request);

        if($campusForm->isSubmitted()&&$campusForm->isValid()) {
            $campusRepository->save($campus, true);
            $this->addFlash('success', 'Campus Created !');
            return $this->redirectToRoute('list_campus');
        }
        return $this->render('campus/addCampus.html.twig', [

            'campusForm' => $campusForm->createView()

        ]);
    }
    #[Route('/campus', name: 'list_campus')]
    public function listCampus(CampusRepository $campusRepository): Response
    {
        //TODO campus search bar

        $campus = $campusRepository->findAll();

        return $this->render('campus/listCampus.html.twig', ['campus' => $campus]);
    }

    #[route('/campus/{id}', name: 'delete_campus', requirements: ['id' =>'\d+'])]
    public function delete(int $id,CampusRepository $campusRepository)
    {

        $campus = $campusRepository->find($id);
        $campusRepository->remove($campus, true);

        $this->addFlash('success', $campus->getName() ." has been removed !");

        return $this-> redirectToRoute('list_campus');

    }

    #[route('/campus/update/{id}', name: 'update_campus', requirements: ['id' =>'\d+'])]
    public function update(int $id,CampusRepository $campusRepository, Request $request)
    {

        $campus = $campusRepository->find($id);
        $campusForm = $this->createForm(CampusType::class, $campus);
        $campusForm->handleRequest($request);

        if ($campusForm->isSubmitted()&&$campusForm->isValid()) {
            $campusRepository->save($campus,true);
            $this->addFlash('success','city Updated !');
            return $this->redirectToRoute('list_campus');
        }
        return $this->render('campus/update.html.twig',['campusForm' => $campusForm->createView()]);


    }





}
