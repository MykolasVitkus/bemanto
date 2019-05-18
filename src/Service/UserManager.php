<?php


namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    private $passwordEncoder;
    private $objectManager;
    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $em)
    {
        $this->passwordEncoder = $encoder;
        $this->objectManager = $em;
    }

    /**
     * @param $email
     * @param $password
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function newAdminUser($email, $password)
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
        $user->setRoles(['ROLE_ADMIN']);
        $this->objectManager->persist($user);
        $this->objectManager->flush();
    }
    public function isEmailTaken($email)
    {
        $repository = $this->objectManager->getRepository(User::class);
        if ($repository->findOneBy(['email' => $email]))
        {
            return true;
        }
        else return false;
    }

}