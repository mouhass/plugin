<?php

// src/Command/CreateUserCommand.php
namespace BatchJobs\BatchJobsBundle\Command;

use BatchJobs\BatchJobsBundle\Controller\MailerController;
use BatchJobs\BatchJobsBundle\Entity\Historique;
use BatchJobs\BatchJobsBundle\Repository\JobCompositeRepository;
use BatchJobs\BatchJobsBundle\Repository\JobCronRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Mailer\MailerInterface;

class saygoodbye extends Command
{
    protected static $defaultName = 'app:saygoodbye';
    private $mailer;
    private $logger;
    private $kernel;
    private $manager;
    private $repository;
    private $managerRegistry;
    public function __construct(string $name = null,MailerInterface $mailer,LoggerInterface $logger,KernelInterface  $kernel,EntityManagerInterface $manager,JobCronRepository $repository, ManagerRegistry $managerRegistry)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->kernel = $kernel;
        $this->manager = $manager;
        $this->repository = $repository;
        $this->managerRegistry = $managerRegistry;
        parent::__construct($name,$manager);
    }


    protected function configure(): void
    {
        $this
            ->addArgument('Related_job', InputArgument::OPTIONAL, 'Whitch one this command is related to?')
            ->addArgument('code_job_composite',InputArgument::OPTIONAL, 'si la commande est lancée à partir de job composite?')
            ->addArgument('dernier_Sous_Job',InputArgument::OPTIONAL,'si c est loe dernier sous job ?')
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $structCommand = new StructCommand($this->manager,$this->managerRegistry,$this->repository,$this->mailer);

        try {

            $jobCron = $this->repository->findElementById($input->getArgument('Related_job'));

            sleep(40);
            //$output->writeln("goodbye goodbye");
//            //$myfile = fopen("webdictionary.txt", "r");
            $output->writeln("goodbye goodbye");
            $log = "command name: app:saygoodbye  state: success  execution date" . ' - ' . date("F j, Y, G:i") . PHP_EOL .
                "-------------------------" . PHP_EOL;
            if($input->getArgument('nom_job_composite')=="0") {
                file_put_contents($this->kernel->getProjectDir() . '/var/log/saygoodbye_succes' . date("y-m-d-G-i-s") . '.log', $log, FILE_APPEND);
            }
            else{
                file_put_contents($this->kernel->getProjectDir() . '/var/log/saygoodbye_succes'.$input->getArgument('nom_job_composite') . date("y-m-d-G-i-s") . '.log', $log, FILE_APPEND);

            }
            $structCommand->ajoutHistoriqueSucces($input,$jobCron);
            //une partie de changement d'état
            $structCommand->modifierEtatJobCronSucces($jobCron);

            if ($input->getArgument('dernier_Sous_Job') == "1") {
                $jobCompositeRepo = new JobCompositeRepository($this->managerRegistry);
                $jobComposite = $jobCompositeRepo->findByCode(strval($input->getArgument('code_job_composite')));
                $structCommand->modifierEtatJobCompositeSucces($jobComposite);

            }
        }
        catch(\Exception $exception){
            $jobCron = $this->repository->findElementById($input->getArgument('Related_job'));
            //une partie de changement d'état dans le cas d'une erreur dans l'exec
            $structCommand->modifierEtatJobCronError($jobCron);
            $log = "command name: app:saytest  state: error  error date" . ' - ' . date("F j, Y, G:i") . PHP_EOL .
                "error description : ".$exception.
                "-------------------------" . PHP_EOL;
            file_put_contents($this->kernel->getProjectDir() . '/var/log/saytest_error' . date("y-m-d-G-i-s") . '.log', $log, FILE_APPEND);
            //une partie d'ajout d'historique dans le cas d'erreur
            $structCommand->ajoutHistoriqueError($input,$jobCron,'saytest');
            if($input->getArgument('code_job_composite')=="0") {
                $structCommand->EnvoyerEmailErrorCron($jobCron);
            }
            if($input->getArgument('code_job_composite')!="0") {
                $jobCompositeRepo = new JobCompositeRepository($this->managerRegistry);
                $jobComposite = $jobCompositeRepo->findByCode($input->getArgument('code_job_composite'));
                $structCommand->EnvoyerEmailErrorComposite($jobComposite,$jobCron);
                $structCommand->modifierEtatJobCompositeError($input,$jobComposite);
            }
        }
        return(1);
    }
}
