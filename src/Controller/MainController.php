<?php

namespace App\Controller;

use App\Entity\HomeFilter;
use App\Form\HomeFilterType;
use App\Repository\EventRepository;
use App\Repository\HomeFilterRepository;
use App\Repository\StateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/home', name: 'main_home')]
        public function list(
            EventRepository $eventRepository,
            StateRepository $stateRepository,
            Request $request,
        ): Response
    {
        $event = $eventRepository->findAll();
        $state = $stateRepository->findOneBy(['tag'=>'closed']);
        $now = new \DateTime('now');

        foreach ($event as $e){

            $dateTimeStart = $e->getDateTimeStart();

            if (($dateTimeStart->modify('+1 month')) < $now) {

                $e->setState($state);
                $eventRepository->save($e, true);
            }
        }

        $filterForm = $this->createForm(HomeFilterType::class);
        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            //$event = $eventRepository->findByFilters($filterForm);
            return $this->render('main/home.html.twig', [
               'events' => $event,
               'researchForm' => $filterForm->createView()
            ]);
        } else {
            return $this->render('main/home.html.twig', [
                'events' => $event,
                'researchForm' => $filterForm->createView()
            ]);
        }
    }
}
