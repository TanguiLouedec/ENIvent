<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\StateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/home', name: 'main_home')]
        public function list(EventRepository $eventRepository, StateRepository $stateRepository): Response
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

        return $this->render('main/home.html.twig', ['events'=>$event]);
    }
}
