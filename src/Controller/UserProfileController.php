<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class UserProfileController extends AbstractController
{
    /**
     * @Route("/profile/{userName}", name="app_userProfile")
     */
    public function index($userName)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
            'email' => $userName
        ]);

        return $this->render('user_profile/user_profile.html.twig', [
            'pageTitle' => 'Profilis',
            'user' => $user
        ]);
    }
}
