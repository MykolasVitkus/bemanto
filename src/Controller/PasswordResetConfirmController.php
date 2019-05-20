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
            $errorMessage = "Įvyko klaida bandant atidaryti šį puslapį. Prašome atidaryti puslapį iš naujo naudojant el. laiške gautą nuorodą. Jei tai kartosis prašome susisiekti su administratoriumi.";
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

            $successMessage = "Jūsų slaptažodis pakeistas sėkmingai. Galite prisijungti prie savo paskyros.";
        }

        return $this->render('password_reset_confirm/pass_reset_confirm.html.twig', [
            'pageTitle' => "Slaptažodžio keitimas",
            'token' => $token,
            'email' => $email,
            'errorMessage' => $errorMessage,
            'successMessage' => $successMessage,
            'password_form' => $form->createView()
        ]);
    }
}
