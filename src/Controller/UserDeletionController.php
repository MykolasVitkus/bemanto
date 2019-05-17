<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Debug\Debug;

class UserDeletionController extends AbstractController
{
    /**
     * @Route("/admin/deletion", name="user_deletion")
     */


    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(User::class);
        $allUsers = $repository->findAll();
        $users = [];

        foreach($allUsers as $user)
        {
            if(!in_array("ROLE_ADMIN",$user->getRoles()))
            {
              array_push($users,$user);
            }
        }

        return $this->render('user_deletion/index.html.twig', [
            'usersArray' => $users,
        ]);
    }


    /**
     * @Route("/admin/deletion/{id}", name="admin_deleteuser")
     */

    public function deleteUser($id)

    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->find($id);
        $em->remove($users);
        $em->flush();
        return $this->redirectToroute('user_deletion');
    }
}
