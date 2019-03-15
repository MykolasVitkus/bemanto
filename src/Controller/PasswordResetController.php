<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\PasswordResetType;
use App\Entity\User;
use Symfony\Component\Routing\Generator\UrlGenerator;

class PasswordResetController extends AbstractController
{
    /**
     * @Route("/password_reset", name="password_reset")
     */
    public function index(Request $request, \Swift_Mailer $mailer)
    {
        $form = $this->createForm(PasswordResetType::class, [
            'action' => $this->generateUrl('password_reset')
        ]);

        $form->handleRequest($request);
        $errorMessage = "Password recovery link was sent to your email. Please check your inbox and follow the instructions.";
        $errorType = "success";
        $errorTitle = "Success! ";

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
                'email' => $form->get('email')->getData()
            ]);

            if(isset($user))
            {
                $hashedEmail = password_hash($user->getEmail(), PASSWORD_BCRYPT);
                $generatedUrl = $this->generateUrl('password_reset_confirm', ['token' => $hashedEmail, 'email' => $user->getEmail()], UrlGenerator::ABSOLUTE_URL);

                $emailMessage = (new \Swift_Message('Password reset'))
                    ->setFrom('bemantelio@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'password_reset/pass_reset_message.html.twig',
                            [
                                'userEmail' => $user->getEmail(),
                                'generatedUrl' => $generatedUrl ]//'hashedEmail' => $hashedEmail] //'tokenUrl' => $tokenUrl]
                        ),
                        'text/html'
                    );

                $mailer->send($emailMessage);
            }
            else
            {
                $errorType = "danger";
                $errorTitle = "Oops... ";
                $errorMessage = "The given email was not found. Please check your email and try again.";
            }
        }

        return $this->render('password_reset/pass_reset.html.twig', [
            'pageTitle' => 'Password reset',
            'errorMessage' => $errorMessage,
            'errorType' => $errorType,
            'errorTitle' => $errorTitle,
            'email_form' => $form->createView(),
        ]);
    }
}
