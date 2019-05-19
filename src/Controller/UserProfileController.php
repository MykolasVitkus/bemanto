<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class UserProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="app_myProfile")
     * redirects user to his own profile
     */
    public function viewProfileWithoutId()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        return $this->redirectToRoute('app_userProfile', [
            'id' => $user->getId()
        ]);
    }

    /**
     * @Route("/profile/{id}", name="app_userProfile")
     */
    public function viewProfile($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
            'id' => $id
        ]);
        
        $myProfile = false;
        $loggedUser = $this->get('security.token_storage')->getToken()->getUser();
        if ($id == $loggedUser->getId()) {
            $myProfile = true;
        }
        $invalidUser = false;
        if (!isset($user)) {
            $invalidUser = true;
        }

        return $this->render('user_profile/user_profile.html.twig', [
            'pageTitle' => 'Profilis',
            'user' => $user,
            'invalidUser' => $invalidUser,
            'isOwnProfile' => $myProfile
        ]);
    }
}
