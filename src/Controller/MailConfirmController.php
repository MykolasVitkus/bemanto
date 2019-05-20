<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\Routing\Generator\UrlGenerator;
use App\Service\EmailManager;

class MailConfirmController extends AbstractController
{
    /**
     * @Route("/confirm", name="confirm_email")
     */
    public function index(Request $request, EmailManager $emailManager)
    {
        $token = $request->query->get('token');
        $user= $this->get('security.token_storage')->getToken()->getUser();
        $isVerified = $user->getVerified();
        
        $msg = "Patvirtinimo žinutė buvo išsiųsta adresu " . $user->getEmail();
            if(isset($user) && !isset($token) && !$isVerified)
            {
                $tokenToHash = $user->getEmail();
                $hashedToken = password_hash($tokenToHash, PASSWORD_BCRYPT);
                $generatedUrl = $this->generateUrl('confirm_email', ['token' => $hashedToken], UrlGenerator::ABSOLUTE_URL);

                $emailManager->sendEmail(
                    'Paskyros patvirtinimas',
                    $user->getEmail(),
                    'email_confirm/email_confirm_message.html.twig',
                    'text/html', 
                    [
                        'generatedUrl' => $generatedUrl
                    ]
                );
            }
            else if($isVerified)
            {
                $msg = "Paskyra jau patvirtinta.";
            }
            else if(password_verify($user->getEmail(), $token))
            {
                $user->setVerified(TRUE);
                $msg = "Paskyra patvirtinta sėkmingai!";
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();
            }
            else
            {
                $msg = "Kažkas įvyko negerai!";
            }

            return $this->render('email_confirm/email_confirm.html.twig', [
                'msg' => $msg,
            ]);
    }
}
