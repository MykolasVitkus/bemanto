<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Event;
use App\Form\EventCreateType;

class EventController extends AbstractController
{
    /**
     * @Route("/events", name="event")
     * @Security("is_granted('ROLE_USER')")
     */
    public function index()
    {
        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();
        return $this->render('events/index.html.twig', [
            'controller_name' => 'Events',
            'events' => $events
        ]);
    }

    /**
     * @Route("/events/create", name="event_create")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function create(Request $request)
    {
        $event = new Event();

        $form = $this->createForm(EventCreateType::class, [
            'action' => $this->generateUrl('event_create')
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $searchEvent = $this->getDoctrine()->getRepository(Event::class)->findOneBy([
                'title' => $form->get('title')->getData()
            ]);

            if(isset($searchEvent))
            {
                $this->addFlash('danger', 'Renginys tokiu pavadinimu jau yra sukurtas!');
            }
            else
            {
                $event->setTitle($form->get('title')->getData());
                $event->setDescription($form->get('description')->getData());
                $event->setDate($form->get('date')->getData());
                $event->setPrice($form->get('price')->getData());
                $event->setLocation($form->get('location')->getData());
                $event->setCategory($form->get('category')->getData());

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($event);
                $entityManager->flush();

                return $this->redirectToRoute('event');
            }
        }

        return $this->render('events/create.html.twig', [
            'pageTitle' => 'Renginio kūrimas',
            'create_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/events/event_edit/{id}", name="event_edit")
     */
    public function edit(Request $request, $id)
    {
        $event = $this->getDoctrine()->getRepository(Event::class)->findOneBy([
            'id' => $id
        ]);

        $form = $this->createForm(EventCreateType::class, $event, [
            'action' => $this->generateUrl('event_edit', [ 'id' => $id ])
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $searchEvent = $this->getDoctrine()->getRepository(Event::class)->findOneBy([
                'title' => $form->get('title')->getData()
            ]);

            if(isset($searchEvent))
            {
                $this->addFlash('danger', 'Renginys tokiu pavadinimu jau yra sukurtas!');
            }
            else
            {
                $event->setTitle($form->get('title')->getData());
                $event->setDescription($form->get('description')->getData());
                $event->setDate($form->get('date')->getData());
                $event->setPrice($form->get('price')->getData());
                $event->setLocation($form->get('location')->getData());
                $event->setCategory($form->get('category')->getData());
                

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                return $this->redirectToRoute('event');
            }
        }


        return $this->render('events/edit.html.twig', [
            'pageTitle' => 'Renginio redagavimas',
            'edit_form' => $form->createView()
        ]);
    }
    /** 
     * @Route("/events/{id}", name="view_event")
     * @Security("is_granted('ROLE_USER')")
     */
    public function viewEvent($id)
    {
        $event = $this->getDoctrine()->getRepository(Event::class)->findOneBy(['id' => $id]);
        if (!$event) {
            throw $this->createNotFoundException(
                'There is no events with the following id: ' . $id
            );
        }
        return $this->render('events/view.html.twig', [
            'event' => $event
        ]);
    }
}
