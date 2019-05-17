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
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $allCategories = $repository->findAll();
        $categories = [];

        foreach($allCategories as $category)
        {
            array_push($categories, $category);
        }

        return $this->render('subscriptions/index.html.twig', [
            'pageTitle' => 'Sekamos kategorijos',
            'categoriesArray' => $categories
        ]);
    }

    /**
     * @Route("/category_subscription/{id}", name="category_subscription")
     */
    public function subscribe($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository(Category::class)->find($id);
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute('subscriptions');
    }


}
