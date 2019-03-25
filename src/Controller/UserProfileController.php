<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController
{
    /**
     * @Route("/profile/{user}", name="app_userProfile")
     */
    public function index($user)
    {
        return $this->render('user_profile/user_profile.html.twig', [
            'pageTitle' => 'Profilis',
        ]);
    }
}
