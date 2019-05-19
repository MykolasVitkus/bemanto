<?php

namespace App\Controller;

use App\Form\FilterType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Validator\Constraints\DateTime;
use App\Entity\Event;
use App\Form\EventCreateType;
use Symfony\Component\HttpFoundation\File\File;

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

        $form = $this->createForm(EventCreateType::class, $event, [
            'action' => $this->generateUrl('event_create')
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $file = $request->files->get('event_create')['photo'];
            if($file->getClientSize() > 3000000) {
                $this->addFlash('danger', 'Failo dydis yra per didelis.');
            }
            if($file->getClientMimeType() === 'image/png' || $file->getClientMimeType() === 'image/jpeg') {
                $uploads_directory = $this->getParameter('events_directory');
                $filename = md5(uniqid()) . '.' . $file->guessExtension();

                $file->move(
                    $uploads_directory,
                    $filename
                );

                $event->setPhoto($filename);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($event);
                $entityManager->flush();

                return $this->redirectToRoute('event');
            } else {
                $this->addFlash('danger', 'Pateiktas failas yra netinkamas.');
            }
        }

        return $this->render('events/create.html.twig', [
            'pageTitle' => 'Renginio kūrimas',
            'actionButton' => 'Sukurti renginį',
            'event_form' => $form->createView()
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
            $file = $request->files->get('event_create')['photo'];
            if($file->getClientSize() > 3000000) {
                $this->addFlash('danger', 'Failo dydis yra per didelis.');
            }
            if($file->getClientMimeType() === 'image/png' || $file->getClientMimeType() === 'image/jpeg') {
                $uploads_directory = $this->getParameter('events_directory');
                $filename = md5(uniqid()) . '.' . $file->guessExtension();

                $file->move(
                    $uploads_directory,
                    $filename
                );
                $event->setPhoto($filename);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                return $this->redirectToRoute('event');
            } else {
                $this->addFlash('danger', 'Pateiktas failas yra netinkamas.');
            }
        }


        return $this->render('events/edit.html.twig', [
            'pageTitle' => 'Renginio redagavimas',
            'actionButton' => 'Redaguoti renginį',
            'event_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/events/event_delete/{id}", name="event_delete")
     */
    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $event = $entityManager->getRepository(Event::class)->find($id);
        $entityManager->remove($event);
        $entityManager->flush();

        return $this->redirectToRoute('event');
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
