<?php

namespace BatchJobs\BatchJobsBundle\Controller;

use BatchJobs\BatchJobsBundle\Repository\JobCompositeRepository;
use BatchJobs\BatchJobsBundle\Repository\JobCronRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

     public function index(JobCronRepository $jobCronRepository, JobCompositeRepository $compositeRepository){

         return $this->render('tous.html.twig',
         ['nbreJobCronError'=> $jobCronRepository->calculateJobCronErr(),

             'nbreJobCompositeError'=>$compositeRepository->calculateJobCompErr(),
             'nbreJobCronEnCours'=>$jobCronRepository->calculateJobCronEnCours() ,

             'nbreJobCompositeEnCours'=>$compositeRepository->calculateJobCompEnCours()
             ]
         );
     }


}
