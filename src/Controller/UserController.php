<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\StateRepository;
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
        $eventState = $event->getState();
        $currentEventState = $eventState->getTag();
        $eventDateLimit = $event->getDateLimitRegistration();
        $eventParticipantsLimit = $event->getNumMaxRegistration();
        $eventUsers = $event->getUsers();
        $currentParticipants = count($eventUsers);
        $today = new \DateTime('now');



        if ($currentEventState == 'open' && $eventDateLimit > $today && $eventParticipantsLimit > $currentParticipants) {

            $user->addEvent($event);
            $userRepository->save($user, true);

            $this->addFlash('success', 'You subscribed to the event !');
            return $this->redirectToRoute('event_detail', ['id' => $event->getId()]);
        } else {
            $this->addFlash('error', 'You dont meet the requirements to subscribe !');
            return $this->redirectToRoute('main_home');
        }
    }
    #[Route('/user/cancel/{id}', name: 'user_cancel', requirements : ["id"=> "\d+"])]
    public function cancel(
        UserRepository $userRepository,
        EventRepository $eventRepository,
        int $id
    ): Response
    {
    $event = $eventRepository->find($id);
    $usersSubscribed = $event->getUsers();

    $user = $this->getUser();
    $eventDateLimit = $event->getDateLimitRegistration();
    $today = new \DateTime('now');

    if ($eventDateLimit > $today && $usersSubscribed->contains($user)){

        $event->removeUser($user);
        $eventRepository->save($event, true);

        $this->addFlash('success', 'Your participation has been canceled !');
        return $this->redirectToRoute('main_home');

    }else {
        $this->addFlash('error', 'You someting wrong hapenned !');
        return $this->redirectToRoute('main_home');
    }
    }

}