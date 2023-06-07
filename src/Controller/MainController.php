<?php

namespace App\Controller;

use App\Form\HomeFilterType;
use App\Repository\EventRepository;
use App\Repository\StateRepository;
use App\Repository\UserRepository;
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
            UserRepository $userRepository,
            Request $request,
        ): Response
    {
        $researchForm = $this->createForm(HomeFilterType::class);

        $event = $eventRepository->findAll();
        $state = $stateRepository->findOneBy(['tag'=>'closed']);
        $now = new \DateTime('now');
        $user = $this->getUser();
        $allUsers = $userRepository->findAll();

        foreach ($event as $e){

            $dateTimeStart = $e->getDateTimeStart();
            $dateTimeStartPlusMonth =($dateTimeStart->modify('+1 month'));
            if ($dateTimeStartPlusMonth < $now) {

                $e->setState($state);
                $eventRepository->save($e, true);
            }
        }

        $filterForm = $this->createForm(HomeFilterType::class);
        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {

            $qb = $eventRepository->createQueryBuilder('eq');
            $campus = $filterForm->get('campus')->getData();
            $beginDate = $filterForm->get('startDate')->getData();
            $endDate = $filterForm->get('endDate')->getData();
            $searchField = $filterForm->get('searchField')->getData();
            $user = $this->getUser();

            if($campus) {
                $qb->leftJoin('eq.campus', 'camp');
                $qb->addSelect('camp');
            }
            return $this->render('main/home.html.twig', [
                'events'=>$qb,
                'researchForm' => $researchForm->createView()
            ]);
        }

        return $this->render('main/home.html.twig', [
            'events'=>$event,
            'researchForm' => $researchForm->createView(),
            'user' =>$user,
            'allUsers' =>$allUsers,
            'now' => $now
            ]);
    }
}
