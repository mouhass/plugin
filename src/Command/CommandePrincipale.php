<?php

namespace BatchJobs\BatchJobsBundle\Command;

use BatchJobs\BatchJobsBundle\Message\LogCommand;
use BatchJobs\BatchJobsBundle\Repository\JobCompositeRepository;
use BatchJobs\BatchJobsBundle\Repository\JobCronRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CommandePrincipale extends Command
{
    protected static $defaultName = 'app:commandeprincipale';

    private $jobCronRepository;
    private $bus;
    private $manager;
    private $jobCompositeRepository;
    public function __construct(string $name = null,JobCronRepository $jobCronRepository,JobCompositeRepository $jobCompositeRepository,MessageBusInterface $bus,EntityManagerInterface $manager)
    {
        $this->jobCronRepository = $jobCronRepository;
        $this->bus = $bus;
        $this->manager = $manager;
        $this->jobCompositeRepository = $jobCompositeRepository;
        parent::__construct($name);
    }
    public function configure()
    {

    }
    protected function readJobsCron(JobCronRepository $repository  ){
        $jobs  = $repository->findAll();
        return $jobs;
    }

    protected  function readJobsComposites(JobCompositeRepository  $repository){
        $jobs = $repository->findAll();
        return $jobs;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $hello1 = $this->readJobsCron($this->jobCronRepository);
        $hello2 = $this->readJobsComposites($this->jobCompositeRepository);
        $ActuDate =  date('i G j n w', strtotime('+1 minute'));
       for($x=0;$x<count($hello1);$x++){
           if(     $hello1[$x]->getScriptExec() != "app:commandeprincipale" and
               $hello1[$x]->nextDateCron($hello1[$x]->getExpression())==$ActuDate and
                   $hello1[$x]->getActif()==true)
           {
               $message = new LogCommand($hello1[$x]->getScriptExec(), $hello1[$x]->getId(), "0", "0");
               $this->bus->dispatch($message);
               $hello1[$x]->setState("en cours");
               $this->manager->persist($hello1[$x]);
               $this->manager->flush();
           }
       }

       for($x=0;$x<count($hello2);$x++){
           $lesSousJobs = $hello2[$x]->getListSousJobs();
           if($hello2[$x]->nextDateCron($hello2[$x]->getExpression())==$ActuDate and $hello2[$x]->getActif()==true) {
               for ($y = 0; $y < count($lesSousJobs); $y++) {
                   if ($y != count($lesSousJobs) - 1) {
                       $message = new LogCommand($lesSousJobs[$y]->getScriptExec(), $lesSousJobs[$y]->getId(), strval($hello2[$x]->getCode()), "0");
                   } else {
                       $message = new LogCommand($lesSousJobs[$y]->getScriptExec(), $lesSousJobs[$y]->getId(), strval($hello2[$x]->getCode()), "1");
                   }
                   $this->bus->dispatch($message);
                   $hello2[$x]->setState("en cours");
                   $this->manager->persist($hello2[$x]);
                   $this->manager->flush();

               }
           }
//       }
       return "finished";
    }}


}
