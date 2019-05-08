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
        $avatar = '3ab8a545f258e526f51246c34ec51b79.png';

        $invalidUser = false;
        if(!isset($user))
        {
            $invalidUser = true;
            $user = $this->get('security.token_storage')->getToken()->getUser();
        }

        return $this->render('user_profile/user_profile.html.twig', [
            'pageTitle' => 'Profilis',
            'user' => $user,
            'avatar' => $avatar,
            'invalidUser' => $invalidUser,
        ]);
    }
}
