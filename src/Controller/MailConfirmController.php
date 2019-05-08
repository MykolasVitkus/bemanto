<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\Routing\Generator\UrlGenerator;

class MailConfirmController extends AbstractController
{
    /**
     * @Route("/confirm", name="confirm_email")
     */
    public function index(Request $request, \Swift_Mailer $mailer)
    {
        $token = $request->query->get('token');
        $user= $this->get('security.token_storage')->getToken()->getUser();
        $isVerified = $user->getVerified();
        
        $msg = "Confirmation message has been sent to " . $user->getEmail();
            if(isset($user) && !isset($token) && !$isVerified)
            {
                $tokenToHash = $user->getEmail();
                $hashedToken = password_hash($tokenToHash, PASSWORD_BCRYPT);
                $generatedUrl = $this->generateUrl('confirm_email', ['token' => $hashedToken], UrlGenerator::ABSOLUTE_URL);

                $emailMessage = (new \Swift_Message('Email confirmation'))
                    ->setFrom('bemantelio@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'email_confirm/email_confirm_message.html.twig',
                            [
                                'generatedUrl' => $generatedUrl ]
                        ),
                        'text/html'
                    );

                $mailer->send($emailMessage);
            }
            else if($isVerified)
            {
                $msg = "You are already verified.";
            }
            else if(password_verify($user->getEmail(), $token))
            {
                $user->setVerified(TRUE);
                $msg = "You are now verified.";
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();
            }
            else
            {
                $msg = "Something went wrong.";
            }

            return $this->render('email_confirm/email_confirm.html.twig', [
                'msg' => $msg,
            ]);
    }
}
