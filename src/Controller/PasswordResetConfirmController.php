<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
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

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
            'email' => $email
        ]);

        $errorMessage = null;
        $successMessage = null;
        $tokenToVerify = $user->getEmail() . ':' . $user->getPassword();

        if(!password_verify($tokenToVerify, $token))
        {
            $errorMessage = "There was a mistake trying to open this page. Please re-open the page by using a link from an email that we sent. If this keeps happening please contact website administrator.";
        }

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user, 
                    $form->get('password')->getData()
                )
            );

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
