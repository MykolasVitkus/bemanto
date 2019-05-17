<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Debug\Debug;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\PaginatorInterface;

class UserDeletionController extends AbstractController
{
    
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(User::class);
        $allUsers = $repository->findAll();
        $users = [];

        foreach ($allUsers as $user) {
            if (!in_array("ROLE_ADMIN", $user->getRoles())) {
                array_push($users, $user);
            }
        }

        return $this->render('user_deletion/index.html.twig', [
            'usersArray' => $users,
        ]);
    }


    /**
     * @Route("/admin/deletion", name="user_deletion")
     */
    public function listAction(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        $dql   = "SELECT a FROM App:User a WHERE a.roles NOT LIKE '%ROLE_ADMIN%' ";
        $query = $em->createQuery($dql);
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            25 /*limit per page*/
        );

        // parameters to template
        return $this->render('user_deletion/index.html.twig', [
            'pagination' => $pagination
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
