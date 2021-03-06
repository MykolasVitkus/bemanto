<?php

namespace App\Controller;

use App\Form\FilterType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Validator\Constraints\DateTime;
use App\Entity\Event;
use App\Entity\Comment;
use App\Form\EventCreateType;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\File\File;
use App\Entity\Category;
use App\Service\EmailManager;
use Symfony\Component\Routing\Generator\UrlGenerator;

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
            ->select("Event")
            ->orderBy('A.id', 'DESC');
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
            'controller_name' => 'Renginiai',
            'events' => $events,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/events/create", name="event_create")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function create(Request $request, EmailManager $emailManager)
    {
        $event = new Event();

        $form = $this->createForm(EventCreateType::class, $event, [
            'action' => $this->generateUrl('event_create')
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('event_create')['photo'];
            if ($file->getClientSize() > 3000000) {
                $this->addFlash('danger', 'Failo dydis yra per didelis.');
            }
            if ($file->getClientMimeType() === 'image/png' || $file->getClientMimeType() === 'image/jpeg') {
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

                $this->sendSubscriptionEmail($event, $emailManager);

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
            'action' => $this->generateUrl('event_edit', ['id' => $id])
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('event_create')['photo'];
            if ($file->getClientSize() > 3000000) {
                $this->addFlash('danger', 'Failo dydis yra per didelis.');
            }
            if ($file->getClientMimeType() === 'image/png' || $file->getClientMimeType() === 'image/jpeg') {
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
    public function viewEvent(Request $request, $id, EntityManagerInterface $manager, PaginatorInterface $paginator)
    {
        $event = $this->getDoctrine()->getRepository(Event::class)->findOneBy(['id' => $id]);
        if (!$event) {
            throw $this->createNotFoundException(
                'Renginys šiuo ID nerastas: ' . $id
            );
        }
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $isSubscribed = false;
        if (in_array($event->getCategory()->getName(), $user->getSubscribedCategories()->toArray())) {
            $isSubscribed = true;
        }
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('view_event', ['id' => $event->getId()])
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($user);
            $comment->setEvent($event);
            $comment->setDate(\DateTime::createFromFormat('Y-m-d', (date("Y-m-d"))));


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('view_event', ['id' => $id]);
        }
        $qb = $manager->createQueryBuilder()
        ->from('App:Comment', 'Comment')
        ->select("Comment")
        ->where("Comment.event = :event")
            ->setParameter('event', $id)
        ->orderBy('Comment.date', 'DESC');
        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('events/view.html.twig', [
            'event' => $event,
            'user' => $user,
            'id'=> $event->getId(),
            'subscribed' => $isSubscribed,
            'comment_form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/comments/delete/{id}", name="comment_delete")
     */
    public function deleteComment($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $comment = $entityManager->getRepository(Comment::class)->find($id);
        $comment_event_id = $comment->getEvent()->getId();
        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->redirectToRoute('view_event', ['id' => $comment_event_id]);
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

    private function sendSubscriptionEmail($event, EmailManager $emailManager)
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $category = $this->getDoctrine()->getRepository(Category::class)->findOneBy([
            'id' => $event->getCategory()
        ]);
        $generatedUrl = $this->generateUrl('view_event', ['id' => $event->getId()], UrlGenerator::ABSOLUTE_URL);
        $subscriptionsUrl = $this->generateUrl('subscriptions', [], UrlGenerator::ABSOLUTE_URL);

        foreach($users as $user)
        {
            if($user->isSubscribedCategory($category))
            {
                $emailManager->sendEmail(
                    'Paskelbtas naujas renginys',
                    $user->getEmail(),
                    'events/subscription_message.html.twig',
                    'text/html', 
                    [
                        'categoryName' => $category->getName(),
                        'eventUrl' => $generatedUrl,
                        'subscriptionsUrl' => $subscriptionsUrl
                    ]
                );
            }
        }
    }
}
