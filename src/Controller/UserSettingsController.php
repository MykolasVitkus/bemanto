<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\UserPasswordChangeType;
use Symfony\Component\HttpFoundation\Request;

class UserSettingsController extends AbstractController
{
    /**
     * @Route("/account_settings", name="app_userSettings")
     */
    public function index()
    {
        return $this->render('user_settings/user_settings.html.twig', [
            'pageTitle' => 'Paskyros nustatymai',
            'blockToShow' => 0,
            'blockTitle' => 'Paskyros apžvalga',
        ]);
    }

    /**
     * @Route("/account_settings/password", name="app_changePassword")
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user= $this->get('security.token_storage')->getToken()->getUser();


        $form = $this->createForm(UserPasswordChangeType::class);
        $form->handleRequest($request);

        $msg = null;
        $wrongPassword = false;

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

                $msg = 'Slaptažodis pakeistas sėkmingai!';
            }
            else
            {
                $wrongPassword = true;
            }
        }

        return $this->render('user_settings/user_settings.html.twig', [
            'pageTitle' => 'Paskyros nustatymai',
            'changePasswordForm' => $form->createView(),
            'errorMessage' => $msg,
            'blockToShow' => 1,
            'blockTitle' => 'Slaptažodžio keitimas',
            'wrongPassword' => $wrongPassword,
        ]);
    }

    /**
     * @Route("/account_settings/email", name="app_changeEmail")
     */
    public function emailChange()
    {
        return $this->render('user_settings/user_settings.html.twig', [
            'pageTitle' => 'Paskyros nustatymai',
            'blockToShow' => 2,
            'blockTitle' => 'El. pašto adreso keitimas',
        ]);
    }
}
