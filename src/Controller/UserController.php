<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    #[Route('/user/apply/{id}', name: 'user_apply', requirements : ["id"=> "\d+"])]
        public function apply(
        UserRepository $userRepository,
        EventRepository $eventRepository,
        int $id
    ): Response
    {

        $event = $eventRepository->find($id);
        $user = $this->getUser();

        $user->addEvent($event);

        $userRepository->save($user,true);


        return $this->redirectToRoute('event_detail',['id'=>$event->getId()]);
    }

}