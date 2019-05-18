<?php

namespace App\Controller;

use App\Form\FilterType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function viewEvents(Request $request, EntityManagerInterface $manager, PaginatorInterface $paginator)
    {
        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();
        $qb = $manager->createQueryBuilder()
            ->from('App:Event', 'Event')
            ->select("Event");
        $form = $this->createForm(FilterType::class);
        $form->handleRequest($request);
        $formData = $form->getData();
        $this->generateFilterQuery($formData, $qb);
        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('events/index.html.twig', [
            'pagination' => $pagination,
            'controller_name' => 'Events',
            'events' => $events,
            'form' => $form->createView()
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

    private function generateFilterQuery($formData, $queryBuilder)
    {
        if (isset($formData) && isset($formData['title'])) {
            $queryBuilder->andWhere("Event.title LIKE :title")
                ->setParameter('title', '%' . $formData['title'] . '%');
        }
        if (isset($formData) && isset($formData['category'])) {
            $queryBuilder->andWhere("Event.category = :category")
                ->setParameter('category', $formData['category']->getId());
        }
        if (isset($formData) && isset($formData['dateFrom'])) {
            $queryBuilder->andWhere("Event.date >= :dateFrom")
                ->setParameter('dateFrom', date("Y-m-d H:i:s", strtotime($formData['dateFrom'])));
        }
        if (isset($formData) && isset($formData['dateTo'])) {
            $queryBuilder->andWhere("Event.date <= :dateTo")
                ->setParameter('dateTo', date("Y-m-d H:i:s", strtotime($formData['dateTo'])));
        }
        if (isset($formData) && isset($formData['priceFrom'])) {
            $queryBuilder->andWhere("Event.price >= :priceFrom")
                ->setParameter('priceFrom', ($formData['priceFrom']));
        }
        if (isset($formData) && isset($formData['priceTo'])) {
            $queryBuilder->andWhere("Event.price <= :priceTo")
                ->setParameter('priceTo', ($formData['priceTo']));
        }
    }
}
