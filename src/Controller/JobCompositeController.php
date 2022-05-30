<?php

namespace BatchJobs\BatchJobsBundle\Controller;

use BatchJobs\BatchJobsBundle\Entity\JobComposite;
use BatchJobs\BatchJobsBundle\Entity\JobCompositeSearch;

use BatchJobs\BatchJobsBundle\Form\CreateNewJobCompositeType;
use BatchJobs\BatchJobsBundle\Form\EditJobCompositeType;
use BatchJobs\BatchJobsBundle\Form\JobCompositeSearchType;

use BatchJobs\BatchJobsBundle\Message\LogCommand;
use BatchJobs\BatchJobsBundle\Repository\JobCompositeRepository;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class JobCompositeController extends AbstractController
{
    private $manager;
    private $bus;
    public function __construct(EntityManagerInterface $manager,MessageBusInterface $bus)
    {
        $this->manager = $manager;
        $this->bus = $bus;
    }

    public function index(PaginatorInterface $paginator,JobCompositeRepository $jobCompositeRepository,Request $request): Response
    {
        $search = new JobCompositeSearch();
        $form = $this->createForm(JobCompositeSearchType::class,$search);
        $form->handleRequest($request);

        $jobComposite = $paginator->paginate($jobCompositeRepository->findSpecific($search), $request->query->getInt('page',1),4);
        return $this->render('@batchJobs/job_composite/index.html.twig', [
            'job_composites' => $jobComposite,
            'form'=>$form->createView()
        ]);
    }


    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $jobComposite = new JobComposite();
        $form = $this->createForm(CreateNewJobCompositeType::class, $jobComposite);
        $form->handleRequest($request);

        $jobComposite->setActif(1);
        $jobComposite->setState("NOUVEAU");
        $jobComposite->setCode(uniqid());

        if ($form->isSubmitted() && $form->isValid()) {

            $listSousJobs = $jobComposite->getListSousJobs();
            for($i=0;$i<count($listSousJobs);$i++)
            {
                $listSousJobs[$i]->setEmailadmincron($listSousJobs->getEmailAdmin().";".$jobComposite->getEmailadmin() );
            }

            $entityManager->persist($jobComposite);
            $entityManager->flush();

            return $this->redirectToRoute('app_job_composite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@batchJobs/job_composite/new.html.twig', [
            'job_composite' => $jobComposite,
            'form' => $form->createView(),
        ]);
    }

    public function show(JobComposite $jobComposite): Response
    {
        return $this->render('@batchJobs/job_composite/show.html.twig', [
            'job_composite' => $jobComposite,
        ]);
    }


    public function edit(Request $request, JobComposite $jobComposite,EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(EditJobCompositeType::class, $jobComposite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $listSousJobs = $jobComposite->getListSousJobs();
            for($i=0;$i<count($listSousJobs);$i++)
            {
                $listSousJobs[$i]->setEmailadmincron(  $listSousJobs[$i]->getEmailadmincron().";".$jobComposite->getEmailadmin());
            }

            $em=$manager;
            $em->persist($jobComposite);
            $em->flush();
            $this->addFlash('success',"un job composite a ete modifié avec succes");
            return $this->redirectToRoute('app_job_composite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@batchJobs/job_composite/edit.html.twig', [
            'job_composite' => $jobComposite,
            'form' => $form->createView(),
        ]);
    }


    public function delete(Request $request, JobComposite $jobComposite, JobCompositeRepository $jobCompositeRepository): Response
    {
        $jobCompositeRepository->remove($jobComposite);
        return $this->redirectToRoute('app_job_composite_index', [], Response::HTTP_SEE_OTHER);
    }


    public function execImm(KernelInterface $kernel,JobComposite $jobComposite){

//        $application = new Application($kernel);
//        $application->setAutoExit(false);
//        $myList = $jobComposite->getListSousJobs();
//        $content = "";
//        for($x=0;$x<=count($myList)-1;$x++){
//            if($myList[$x]->actif) {
//                $input = new ArrayInput(array(
//                    'command' => $myList[$x]->getScriptExec(),
//                    'Related_job'=>$jobComposite->getId()
//                ));
//
//                // Use the NullOutput class instead of BufferedOutput.
//                $output = new BufferedOutput();
//
//                $application->run($input, $output);
//
//                $content = $content . $output->fetch();
//            }
//        }
        $jobComposite->setState("en cours");
        $this->manager->persist($jobComposite);
        $this->manager->flush();
        $myList = $jobComposite->getListSousJobs();
        for($x=0;$x<=count($myList)-1;$x++){
            if($myList[$x]->getActif() ) {
                if($x!=count($myList)-1){
                $message = new LogCommand($myList[$x]->getScriptExec(),$myList[$x]->getId(),$jobComposite->getCode(),"0");
                $this->bus->dispatch($message);}

            if($x==count($myList)-1){
            $message = new LogCommand($myList[$x]->getScriptExec(),$myList[$x]->getId(),$jobComposite->getCode(),"1");
            $this->bus->dispatch($message);
                }
            $myList[$x]->setState("en cours");$this->manager->persist($myList[$x]);$this->manager->flush();

        }}
//

        //dd(count($myList));

        return $this->redirectToRoute('app_job_composite_index', [], Response::HTTP_SEE_OTHER);
    }

    public function verifiverif(JobCompositeRepository $repository ,EntityManagerInterface $entityManager)
    {
        $res = $repository->verifyJobCron("app:sayhello",$entityManager);
       dd($res);

    }

    public function download(){

    }

    public function actifdesactif(JobComposite $jobComposite,EntityManagerInterface $manager){
        if($jobComposite->getActif()){
            $jobComposite->setActif(false);
            $manager->persist($jobComposite);
            $manager->flush();
        }
        else{
            $jobComposite->setActif(true);
            $manager->persist($jobComposite);
            $manager->flush();
        }
        return $this->redirectToRoute('app_job_composite_index', [], Response::HTTP_SEE_OTHER);
    }

}
