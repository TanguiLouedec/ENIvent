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
    #[Route('/add', name:'add')]
    public function add(EventRepository $eventRepository, Request $request){

        $event = new Event();
        $eventForm = $this->createForm(EventType::class,$event);
        $eventForm->handleRequest($request);

        if($eventForm->isSubmitted()&&$eventForm->isValid()){
            $eventRepository->save($event, true);
            $this->addFlash('success','Event successfully added !');
            return $this->redirectToRoute('event_detail',['id'=>$event->getId()]);
        }
        return $this->render('event/add.html.twig', ['eventForm' => $eventForm->createView()]);
    }

    #[Route('/detail/{id}', name:'detail')]
    public function view(int $id, EventRepository $eventRepository):Response{

        $event = $eventRepository->find($id);
        if(!$event){
            throw $this->createNotFoundException("Oops! Event not found");
        }

        return $this->render('event/detail.html.twig', ['event'=>$event]);

    }

    #[Route('/update/{id}', name:'update')]
    public function update(int $id, EventRepository $eventRepository,Request $request){

        $event = $eventRepository->find($id);
        $eventForm = $this->createForm(EventType::class,$event);
        $eventForm->handleRequest($request);

        if($eventForm->isSubmitted()&&$eventForm->isValid()){
            $eventRepository->save($event, true);
            $this->addFlash('success','Event successfully updated !');
            return $this->redirectToRoute('event_detail',['id'=>$event->getId()]);
        }
        return $this->render('event/update.html.twig',['eventFormUpdate'=>$eventForm->createView()]);

    }

    #[Route('/delete/{id}', name:'delete')]
    public function delete(int $id, EventRepository $eventRepository,Request $request){

        $event = $eventRepository->find($id);
        $eventRepository->remove($event, true);
        $this->addFlash('success', $event->getName()."has been deleted");
        return $this->redirectToRoute('event_list');
    }

}
