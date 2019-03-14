<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserDeletionController extends AbstractController
{
    /**
     * @Route("/admin/deletion", name="user_deletion")
     */
    public function index()
    {
        return $this->render('user_deletion/index.html.twig', [
            'controller_name' => 'UserDeletionController',
        ]);
    }
}
