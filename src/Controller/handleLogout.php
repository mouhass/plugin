<?php


namespace BatchJobs\BatchJobsBundle\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class handleLogout extends AbstractController
{
    public function exit(){
       return $this->render('Security/logout.html.twig');
    }

}
