<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\PasswordChangeType;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ChangePasswordController extends AbstractController
{
    /**
     * @Route("/changepw", name="app_changePw")
     */
    public function changePw(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user= $this->get('security.token_storage')->getToken()->getUser();


        $form = $this->createForm(PasswordChangeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
            if($passwordEncoder->encodePassword(
                $user,
                $form->get('currentpw')->getData())==$user->getPassword())
            {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('newpw')->getData()
                )
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('home');
            }
            else
            {
                return $this->redirectToRoute('gay');
            }
        }

        return $this->render('passwordchange/passwordchange.html.twig',[
            'changepasswordForm' => $form->createView(),
        ]);
    }
}
