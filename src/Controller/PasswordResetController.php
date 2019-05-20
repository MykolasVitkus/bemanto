<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\PasswordResetType;
use App\Entity\User;
use Symfony\Component\Routing\Generator\UrlGenerator;
use App\Service\EmailManager;

class PasswordResetController extends AbstractController
{
    /**
     * @Route("/password_reset", name="password_reset")
     */
    public function index(Request $request, \Swift_Mailer $mailer, EmailManager $emailManager)
    {
        $form = $this->createForm(PasswordResetType::class, [
            'action' => $this->generateUrl('password_reset')
        ]);

        $form->handleRequest($request);
        $errorMessage = "Slaptažodžio atstatymo nuoroda buvo nusiųsta į Jūsų el. paštą. Peržiūrėkite savo pašto dėžutę ir sekite instrukcijas.";
        $errorType = "success";
        $errorTitle = null;

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
                'email' => $form->get('email')->getData()
            ]);

            if(isset($user))
            {
                $errorTitle = "Sėkmingai! ";

                $tokenToHash = $user->getEmail() . ':' . $user->getPassword();
                $hashedToken = password_hash($tokenToHash, PASSWORD_BCRYPT);
                $generatedUrl = $this->generateUrl('password_reset_confirm', ['token' => $hashedToken, 'email' => $user->getEmail()], UrlGenerator::ABSOLUTE_URL);

                $emailMessage = $emailManager->sendEmail(
                    'Slaptažodžio keitimas',
                    $user->getEmail(),
                    'password_reset/pass_reset_message.html.twig',
                    'text/html', 
                    [
                        'userEmail' => $user->getEmail(),
                        'generatedUrl' => $generatedUrl
                    ]
                );

                $mailer->send($emailMessage);
            }
            else
            {
                $errorType = "danger";
                $errorTitle = "Ups... ";
                $errorMessage = "Nurodytas el. pašto adresas nerastas. Pasitikrinkite savo adresą ir pabandykite dar kartą.";
            }
        }

        return $this->render('password_reset/pass_reset.html.twig', [
            'pageTitle' => 'Slaptažodžio atstatymas',
            'errorMessage' => $errorMessage,
            'errorType' => $errorType,
            'errorTitle' => $errorTitle,
            'email_form' => $form->createView(),
        ]);
    }
}
