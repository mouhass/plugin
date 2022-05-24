<?php

namespace BatchJobs\BatchJobsBundle\Controller;

use BatchJobs\BatchJobsBundle\Entity\JobCronSearch;
use BatchJobs\BatchJobsBundle\Form\JobCronSearchType;
use BatchJobs\BatchJobsBundle\Repository\JobCompositeRepository;
use BatchJobs\BatchJobsBundle\Repository\JobCronRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

     public function index(Request $request,JobCronRepository $jobCronRepository, JobCompositeRepository $compositeRepository){

         return $this->render('@batchJobs/tous.html.twig',
         ['nbreJobCronError'=> $jobCronRepository->calculateJobCronErr(),

             'nbreJobCompositeError'=>$compositeRepository->calculateJobCompErr(),
             'nbreJobCronEnCours'=>$jobCronRepository->calculateJobCronEnCours() ,

             'nbreJobCompositeEnCours'=>$compositeRepository->calculateJobCompEnCours(),


             ]
         );
     }


}
