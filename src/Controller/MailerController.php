<?php


namespace BatchJobs\BatchJobsBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerController extends AbstractController
{

    public function sendEmail(MailerInterface $mailer,String $sthing): Response
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('hassine.mounir1234@gmail.com')
            ->subject("Erreur dans l'exécution d'un job")
            ->text($sthing)
            ->html($sthing);

        $mailer->send($email);
        return new Response("email was sent");

    }
}
