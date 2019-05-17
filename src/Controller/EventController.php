<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\Event;

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
    public function create() {
        $form = $this->createForm
    }
}
