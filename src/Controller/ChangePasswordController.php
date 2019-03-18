<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\UserPasswordChangeType;
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


        $form = $this->createForm(UserPasswordChangeType::class);
        $form->handleRequest($request);

        $msg = '';


        if ($form->isSubmitted() && $form->isValid()) {

            $currentpw = $user->getPassword();
            $inputpw = $form->get('currentpw')->getData();



            if(password_verify($inputpw, $currentpw))
            {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('newpw')->getData()
                )
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $msg = 'Success.';
            }
            else
            {
                $msg = 'This is not your current password.';
            }
        }

        return $this->render('passwordchange/passwordchange.html.twig',[
            'changepasswordForm' => $form->createView(),
            'displaymsg' => $msg,
        ]);
    }
}
