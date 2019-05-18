<?php


namespace App\Command;

use App\Entity\User;
use App\Service\UserManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/*
 * Command class used to create an admin user.
 * For correct usage, use $ php bin/console app:create-user username password
 */
class CreateAdminUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';

    private $userManager;
    public function __construct(UserManager $manager)
    {
        $this->userManager = $manager;
        parent::__construct();
    }
    protected function configure()
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the user.')
            ->addArgument('password', InputArgument::REQUIRED, 'The password of the user.')
            ->setDescription('Creates a new user.')
            ->setHelp('This command allows you to create a user with administrative roles.
             To use correctly, type php bin/console app:create-user \'yourEmail\' \'yourPassword\' ')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('You are about to create an admin user');
        $email = $input->getArgument('email');
        if (!$this->userManager->isEmailTaken($email))
        {
            $password = $input->getArgument('password');
            $this->userManager->newAdminUser($email, $password);
            $output->writeln('=================================');
            $output->writeln('User has been successfully created!');
            $output->writeln('=================================');
            die;
        }
        $output->writeln('=================================');
        $output->writeln('This email is already taken!');
        $output->writeln('=================================');
    }
}