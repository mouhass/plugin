<?php

// src/Command/CreateUserCommand.php
namespace BatchJobs\BatchJobsBundle\Command;

use BatchJobs\BatchJobsBundle\Controller\MailerController;
use BatchJobs\BatchJobsBundle\Message\LogCommand;
use BatchJobs\BatchJobsBundle\Repository\JobCronRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Process\Process;

class sayhello extends Command
{
    protected static $defaultName = 'app:sayhello';
    private $mailer;
    private $logger;
    private $repository;
   private $bus;

    const MAX_SIMULTANEOUS_PROCESSES = 50;

    public function __construct(string $name = null,MailerInterface $mailer,LoggerInterface $logger,MessageBusInterface  $bus,JobCronRepository $repository)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->repository = $repository;
        $this->bus = $bus;
        parent::__construct($name);
    }


    protected function configure(): void
    {

    }
    protected function readJobs(JobCronRepository $repository){
        $jobs  = $repository->findAll();
        return $jobs;
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
//            $app = $this->getApplication();
            //$hello = $this->readJobs($this->repository);
              $processes = [];
              $hello = ["0"=>"app:saygoodbye","1"=>"app:saywow"];

//                   $message = new LogCommand($hello[$x]);
//                   $this->bus->dispatch($message);
             for($x=0;$x<=count($hello)-1;$x++){
//                $process = new Process (sprintf('php bin/console %s', $hello[$x]->getScriptExec()));
//                $process->start();

                     $message = new LogCommand($hello[$x]);
                     $this->bus->dispatch($message);


            }

            //dd($hello);




//                   while ($wait) {
//                       if (sizeof($processes) > self::MAX_SIMULTANEOUS_PROCESSES) {
//                           sleep(1);
//                           $processes = $this->checkRunningProcesses($processes);
//                       } else {
//                           $wait = false;
//                       }
//                   }


            }

        catch(\Exception $exception){
//            $log = "command name: app:sayhello  state: error  error date" . ' - ' . date("F j, Y, G:i") . PHP_EOL .
//                "error description : ".$exception.
//                "-------------------------" . PHP_EOL;
//            file_put_contents('./var/log/sayhello_error' . date("y-m-d-G-i") . '.log', $log, FILE_APPEND);
//
//            $email = new MailerController();
//            $email->sendEmail($this->mailer, "Un erreur dans l'exécution du job dont la commande est app:sayhello");
            $output->writeln($exception);
        }
        return(1);
    }


}
