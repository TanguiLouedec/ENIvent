<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Location;
use App\Entity\State;
use App\Form\EventCancelType;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/event', name: 'event_')]
class EventController extends AbstractController
{
    #[Route('/add', name: 'add')]
    public function add(EventRepository $eventRepository, Request $request, EntityManagerInterface $entityManager)
    {


        $event = new Event();

        $eventForm = $this->createForm(EventType::class, $event);
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $state = new State();
//            $location = $locationRepository->find($eventForm->get('location')->getData());
//            $event->setLocation($location);
            if ($eventForm->getClickedButton() === $eventForm->get('save')) {
                $state->setTag('saved');
            } else {
                $state->setTag('open');
            }
            $event->setState($state);
            $entityManager->persist($event);
            $entityManager->flush();
            $this->addFlash('success', 'Event successfully added !');
            return $this->redirectToRoute('event_detail', ['id' => $event->getId()]);
        }
        return $this->render('event/add.html.twig', ['eventForm' => $eventForm->createView()]);
    }

    #[Route('/detail/{id}', name: 'detail')]
    public function view(int $id, EventRepository $eventRepository): Response
    {

        $event = $eventRepository->find($id);
        if (!$event) {
            throw $this->createNotFoundException("Oops! Event not found");
        }

        return $this->render('event/detail.html.twig', ['event' => $event]);

    }

    #[Route('/update/{id}', name: 'update')]
    public function update(int $id, EventRepository $eventRepository, Request $request)
    {

        $event = $eventRepository->find($id);
        $eventForm = $this->createForm(EventType::class, $event);
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $state = new State();
            if ($eventForm->getClickedButton() === $eventForm->get('save')) {
                $state->setTag('saved');
            }else if($eventForm->getClickedButton() === $eventForm->get('open')) {
                $state->setTag('open');
            }
            $event->setState($state);
            $eventRepository->save($event, true);
            $this->addFlash('success', 'Event successfully updated !');
            return $this->redirectToRoute('event_detail', ['id' => $event->getId()]);
        }
        return $this->render('event/update.html.twig', ['eventFormUpdate' => $eventForm->createView(), 'event' => $event]);

    }

    #[Route('/cancel/{id}', name: 'cancel')]
    public function cancel(int $id, EventRepository $eventRepository, Request $request)
    {
        $event = $eventRepository->find($id);
        $eventCancelForm = $this->createForm(EventCancelType::class, $event);
        $eventCancelForm->handleRequest($request);

        if ($eventCancelForm->isSubmitted() && $eventCancelForm->isValid()) {
            $state = new State();
            $state->setTag('cancelled');
            $event->setState($state);
            $eventRepository->save($event, true);
            $this->addFlash('success', $event->getName() . "has been canceled");
            return $this->redirectToRoute('event_detail', ['id' => $event->getId()]);
        }
        return $this->render('event/cancel.html.twig', ['eventCancelForm' => $eventCancelForm->createView(), 'event' => $event]);
    }



}
