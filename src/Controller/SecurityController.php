<?php


namespace BatchJobs\BatchJobsBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{


    public function Login(AuthenticationUtils $authenticationUtils){
        $lastUsername = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('Security/login.html.twig', [
            'last_username'=>$lastUsername,
            'error'=>$error
        ]);
    }
}
