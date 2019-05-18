<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;

class SubscriptionsController extends AbstractController
{
    /**
     * @Route("/subscriptions", name="subscriptions")
     */
    public function list()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $allCategories = $repository->findAll();
        $categories = [];

        foreach ($allCategories as $category) {
            array_push($categories, ["category" => $category, "isSub" => $user->isSubscribedCategory($category)]);
        }
    
        return $this->render('subscriptions/index.html.twig', [
            'pageTitle' => 'Sekamos kategorijos',
            'categoriesArray' => $categories
        ]);
    }

    /**
     * @Route("/category_subscribe/{id}", name="category_subscribe")
     */
    public function subscribe($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $category = $this->getDoctrine()->getRepository(Category::class)->findOneBy([
            'id' => $id
        ]);

        $user->addSubscribedCategory($category);
        $entityManager->flush();

        return $this->redirectToRoute('subscriptions');
    }

    /**
     * @Route("/category_unsubscribe/{id}", name="category_unsubscribe")
     */
    public function unsubscribe($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $category = $this->getDoctrine()->getRepository(Category::class)->findOneBy([
            'id' => $id
        ]);

        $user->removeSubscribedCategory($category);
        $entityManager->flush();

        return $this->redirectToRoute('subscriptions');
    }
}
