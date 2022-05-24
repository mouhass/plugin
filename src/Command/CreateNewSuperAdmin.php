<?php


namespace BatchJobs\BatchJobsBundle\Command;


use BatchJobs\BatchJobsBundle\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateNewSuperAdmin extends Command
{
    protected static $defaultName = 'app:createSuperAdmin';
    private $manager;
    private $encoder;
    public function __construct(string $name = null,EntityManagerInterface $manager,UserPasswordEncoderInterface $encoder)
    {
        $this->manager = $manager;
        $this->encoder = $encoder;
        parent::__construct($name);
    }

    public function configure()
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL, 'Whitch one this command is related to?')
            ->addArgument('password',InputArgument::OPTIONAL, 'si la commande est lancée à partir de job composite?')
            ->addArgument('email',InputArgument::OPTIONAL,'si c est loe dernier sous job ?')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $plainPassword = $input->getArgument('password');
        $email = $input->getArgument('email');

        $admin = new Admin();
        $admin->setName($name);
        $admin->setEmail($email);

        $encodedPassword = $this->encoder->encodePassword($admin, $plainPassword);

        $admin->setPassword($encodedPassword);

        $admin->setRoles(['ROLE_SUPER_ADMIN']);

        $this->manager->persist($admin);
        $this->manager->flush();
        $output->write("un super admin a été créé avec succès");

    return(1);
    }
}
