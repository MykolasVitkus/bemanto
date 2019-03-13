<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\PasswordResetType;

class PasswordResetController extends AbstractController
{
    /**
     * @Route("/password_reset", name="password_reset")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(PasswordResetType::class, [
            'action' => $this->generateUrl('password_reset')
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

        }

        return $this->render('password_reset/pass_reset.html.twig', [
            'pageTitle' => 'Password reset',
            'email_form' => $form->createView(),
        ]);
    }
}
