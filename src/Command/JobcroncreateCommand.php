<?php

namespace BatchJobs\BatchJobsBundle\Command;

use BatchJobs\BatchJobsBundle\Entity\Admin;
use BatchJobs\BatchJobsBundle\Entity\JobCron;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class JobcroncreateCommand extends Command
{
    protected static $defaultName = 'jobcroncreate';
    protected static $defaultDescription = 'Add a short description for your command';
    protected $manager;
    public function __construct(string $name = null,EntityManagerInterface $manager)
    {
        parent::__construct($name);
        $this->manager = $manager;

    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
       $job = new JobCron();
       $job->setName("secondJob")
           ->setActif(1)
           ->setCreatedBy($this->manager->getRepository(Admin::class)->find(1))
           ->setExpression("* * * * *")
           ->setNextDateExec(new \DateTime())
           ->setState("NOUVEAU")
           ->setScriptExec("app:sayhello")
       ;
       $this->manager->persist($job);
       $this->manager->flush();
       return(1);

    }
}
