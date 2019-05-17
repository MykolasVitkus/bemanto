<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_cp")
 */
class CategoryEditController extends AbstractController
{
    /**
     * @Route("/categories", name="category_edit")
     */
    public function index()
    {
        return $this->render('category_edit/index.html.twig', [
            'controller_name' => 'CategoryEditController',
        ]);
    }
}
