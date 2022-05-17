<?php
namespace BatchJobs\BatchJobsBundle\MessageHandler;
use BatchJobs\BatchJobsBundle\Message\LogCommand;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class LogCommandHandler  implements MessageHandlerInterface
{
    private $kernel;
    private $registry;
    public function __construct(KernelInterface $kernel,ManagerRegistry $registry)
    {
        $this->kernel = $kernel;
        $this->registry = $registry;
    }

    public function __invoke(LogCommand $command)
    {
        echo "test 2";
        sleep(10);
        exec("php bin/console ".$command->getNameCommand()." ".$command->getIdJobCron().' '.$command->getCodeCommand().' '.$command->getDernierSousJob());
       echo "finished";

    }
}
