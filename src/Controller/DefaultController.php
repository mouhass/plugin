<?php

namespace BatchJobs\BatchJobsBundle\Controller;

use BatchJobs\BatchJobsBundle\m1\LogMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    public function index(MessageBusInterface $bus): Response
    {
        $message = new LogMessage('bonjour je suis un log');
        $bus->dispatch($message);

        $message2 = new LogMessage('Bonjour je suis le message numero 2');
        $bus->dispatch($message2);
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
