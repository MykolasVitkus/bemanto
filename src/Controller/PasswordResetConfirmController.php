<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PasswordResetConfirmController extends AbstractController
{
    /**
     * @Route("/password_reset_confirm", name="password_reset_confirm", methods={"GET", "HEAD"})
     */
    public function index(Request $request)
    {
        $token = $request->query->get('token');
        $email = $request->query->get('email');

        return $this->render('password_reset_confirm/pass_reset_confirm.html.twig', [
            'token' => $token,
            'email' => $email
        ]);
    }
}
