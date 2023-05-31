<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Location;
use App\Entity\State;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/event', name:'event_')]
class EventController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(EventRepository $eventRepository): Response
    {
      $events = $eventRepository ->findAll();
      if(!$events){
          throw  $this->createNotFoundException("Oops ! No events found !");
      }

      return $this->render('event/list.html.twig', ['event'=>$events]);
    }

    #[Route('/add', name:'add')]
    public function add(EventRepository $eventRepository, Request $request){

        $event = new Event();
        $eventForm = $this->createForm(EventType::class,$event);
        $eventForm->handleRequest($request);

        if($eventForm->isSubmitted()&&$eventForm->isValid()){
            $eventRepository->save($event, true);
            $this->addFlash('success','Event successfully added !');
            return $this->redirectToRoute('main_home',['id'=>$event->getId()]);
        }
        return $this->render('event/add.html.twig', ['eventForm' => $eventForm->createView()]);
    }

}
