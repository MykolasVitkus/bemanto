<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\RegistrationFormType;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\PasswordChangeType;

class PasswordResetConfirmController extends AbstractController
{
    /**
     * @Route("/password_reset_confirm", name="password_reset_confirm")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(PasswordChangeType::class, null);

        $form->handleRequest($request);

        $token = $request->query->get('token');
        $email = $request->query->get('email');

        $errorMessage = null;
        $successMessage = null;

        if(!password_verify($email, $token))
        {
            $errorMessage = "There was a mistake trying to open this page. Please re-open the page by using a link from an email that we sent. If this keeps happening please contact website administrator.";
        }

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
                'email' => $email
            ]);

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user, 
                    $form->get('password')->getData()
                )
            );

            $em->persist($user);
            $em->flush();

            $successMessage = "Your password has been changed successfully. You can now login into your account.";
        }

        return $this->render('password_reset_confirm/pass_reset_confirm.html.twig', [
            'pageTitle' => "Password change",
            'token' => $token,
            'email' => $email,
            'errorMessage' => $errorMessage,
            'successMessage' => $successMessage,
            'password_form' => $form->createView()
        ]);
    }
}
