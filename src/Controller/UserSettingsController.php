<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\UserPasswordChangeType;
use App\Form\EmailChangeType;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AvatarChangeType;
use Symfony\Component\Filesystem\Filesystem;

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

                $this->addFlash('success', 'Slaptažodis pakeistas sėkmingai!');
            }
            else
            {
                $this->addFlash('error', '• Neteisingai įvestas esamas slaptažodis!');
            }
        }

        return $this->render('user_settings/user_settings.html.twig', [
            'pageTitle' => 'Paskyros nustatymai',
            'changePasswordForm' => $form->createView(),
            'blockToShow' => 1,
            'blockTitle' => 'Slaptažodžio keitimas',
        ]);
    }

    /** 
     * @Route("/account_settings/email", name="app_changeEmail")
     */
    public function changeEmail(Request $request)
    {
        $form = $this->createForm(EmailChangeType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $givenEmail = $form->get('email')->getData();

            $checkEmail = $this->getDoctrine()->getRepository(User::class)->findOneBy([
                'email' => $givenEmail
            ]);

            if(isset($checkEmail))
            {
                $this->addFlash('error', '• Vartotojas tokiu el. pašto adresu jau egzistuoja');
            }
            else
            {
                $user = $this->get('security.token_storage')->getToken()->getUser();
                $currentPassword = $user->getPassword();
                $givenPassword = $form->get('password')->getData();

                if(password_verify($givenPassword, $currentPassword))
                {
                    $em = $this->getDoctrine()->getManager();
                    $user->setEmail($givenEmail);
                    $em->flush();
                    $this->addFlash('success', 'Paskyros el. pašto adresas pakeistas!');
                }
                else $this->addFlash('wrong', '• Neteisingai įvestas slaptažodis');
            }
        }

        return $this->render('user_settings/user_settings.html.twig', [
            'pageTitle' => 'Paskyros nustatymai',
            'emailChangeForm' => $form->createView(),
            'blockToShow' => 2,
            'blockTitle' => 'El. pašto adreso keitimas',
        ]);
    }

    /**
     * @Route("/account_settings/avatar_change", name="app_avatarChange")
     */
    public function avatarChange(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $avatar = $user->getAvatar();

        $form = $this->createForm(AvatarChangeType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $file = $request->files->all()['avatar_change']['avatar'];

            if($file->getSize() > 1000000)
            {
                $this->addFlash('danger', 'Failas yra per didelis! Didžiausias leistinas dydis 1MB.');
                return $this->redirectToRoute('app_avatarChange');
            }

            $avatarsDirectory = $this->getParameter('avatars_directory');
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            
            $file->move(
                $avatarsDirectory, $fileName
            );

            if($avatar !== "avatar.png")
            {
                $fileSystem = new Filesystem();
                $fileSystem->remove($avatarsDirectory . '/' . $avatar);
            }

            $user->setAvatar($fileName);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('app_avatarChange');
        }

        return $this->render('user_settings/user_settings.html.twig', [
            'pageTitle' => 'Paskyros nustatymai',
            'blockToShow' => 3,
            'blockTitle' => 'Nuotraukos keitimas',
            'avatar' => $avatar,
            'avatar_form' => $form->createView(),
        ]);
    }
}
