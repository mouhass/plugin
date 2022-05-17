<?php

namespace BatchJobs\BatchJobsBundle\Mh1;

use BatchJobs\BatchJobsBundle\m1\LogMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class LogMessageHandler implements MessageHandlerInterface
{
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    //maintenant on va créer la méthode qui va être appelée par defaut
    public function __invoke(LogMessage $logMessage)
    {
        $this->logger->info($logMessage->getMessage());
    }

}
