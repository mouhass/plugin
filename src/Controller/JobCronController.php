<?php
namespace BatchJobs\BatchJobsBundle\Controller;
use BatchJobs\BatchJobsBundle\Entity\Historique;
use BatchJobs\BatchJobsBundle\Entity\JobCron;
use BatchJobs\BatchJobsBundle\Entity\JobCronSearch;
use BatchJobs\BatchJobsBundle\Form\CreateNewJobCronType;
use BatchJobs\BatchJobsBundle\Form\EditJobCronType;
use BatchJobs\BatchJobsBundle\Form\JobCronSearchType;
use BatchJobs\BatchJobsBundle\Message\LogCommand;
use BatchJobs\BatchJobsBundle\Repository\HistoriqueRepository;
use BatchJobs\BatchJobsBundle\Repository\JobCronRepository;
use Cron\CronExpression;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class JobCronController extends AbstractController
{
    private $jobCronRepo;
    private $manager;
    private $bus;
    private $security;
    public function __construct(JobCronRepository $jobCronRepo,EntityManagerInterface $manager,MessageBusInterface  $bus,Security $security)
    {
        $this->jobCronRepo = $jobCronRepo;
        $this->manager = $manager;
        $this->bus = $bus;
        $this->security = $security;
    }



    public function index(PaginatorInterface $paginator,JobCronRepository $jobCronRepository, Request $request): Response
    {
        $search = new JobCronSearch();
        $form = $this->createForm(JobCronSearchType::class,$search);
        $form->handleRequest($request);

        $jobCron = $paginator->paginate($jobCronRepository->findSpecific($search), $request->query->getInt('page',1),4);


        return $this->render('JobCron/index.html.twig', [
            'jobCron' => $jobCron,
            'form'=> $form->createView()
        ]);
    }


    public function new(Request $request, JobCronRepository $jobCronRepository,UserInterface $user): Response
    {
        $jobCron = new JobCron();
        $form = $this->createForm(CreateNewJobCronType::class, $jobCron);
        $form->handleRequest($request);

        $jobCron->setState("NOUVEAU");
        $jobCron->setActif(1);
        $jobCron->setCode(uniqid());

       // $user = $this->getUser()->getUsername();

        $jobCron->setEmailadmincron($user->getUsername());

        if ($form->isSubmitted() && $form->isValid()) {
            $jobCronRepository->add($jobCron);

            return $this->redirectToRoute('app_jobCron_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('JobCron/new.html.twig', [
            'admin' => $jobCron,
            'form' => $form->createView(),
        ]);
    }


    public function show(JobCron $jobCron): Response
    {
       // $jobCron = $this->jobCronRepo->findElementById('secondJobCron');
        return $this->render('JobCron/show.html.twig', [
            'JobCron' => $jobCron,
        ]);
    }


    public function edit(Request $request, JobCron $jobCron, JobCronRepository $repository): Response
    {

        $form = $this->createForm(EditJobCronType::class, $jobCron);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$this->manager;
            $em->persist($jobCron);
            $em->flush();
            $this->addFlash('success',"un job cron a ete modifié avec succes");


            return $this->redirectToRoute('app_jobCron_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('JobCron/edit.html.twig', [
            'JobCron' => $jobCron,
            'form' => $form->createView(),
        ]);
    }



    public function delete(Request $request, JobCron $jobCron, JobCronRepository $jobCronRepository): Response
    {
         if($jobCron->getJobComposites()->toArray()==[]) {
             $jobCronRepository->remove($jobCron);
         }
         else{

         }

        return $this->redirectToRoute('app_jobCron_index', [], Response::HTTP_SEE_OTHER);
        //return new Response(implode() );

    }



    public function execImm(KernelInterface $kernel,JobCron $jobCron){
//        $jobCron->setState("en cours");
//        $this->manager->persist($jobCron);
//        $this->manager->flush();
//        $application = new Application($kernel);
//        $application->setAutoExit(false);
//        $input = new ArrayInput(array(
//            'command' => $jobCron->getScriptExec(),
//            'Related_job'=>$jobCron->getId()
//        ));
//
//        // Use the NullOutput class instead of BufferedOutput.
//        $output = new BufferedOutput();
//
//        $application->run($input, $output);
//
//        $content = $output->fetch();
//        $pr = new Process(sprintf('php bin/console %s %s',  $jobCron->getScriptExec(),$jobCron->getId()));
//        $pr->setWorkingDirectory(__DIR__ . '/../..');
//
//        $pr->start();
//        while ($pr->isRunning()) {
//            $jobCron->setState("en cours");
//            $this->manager->persist($jobCron);
//            $this->manager->flush();
//
//        }
        //envoyer la demande de lancement du job au RabbitMq
        $message = new LogCommand($jobCron->getScriptExec(),$jobCron->getId(),"0","0");
        $this->bus->dispatch($message);
        //changement de l'état du job en lui affectant la valeur "en cours"
        $jobCron->setState("en cours");
        //persistance des changements dans la base de données
        $this->manager->persist($jobCron);
        $this->manager->flush();
        //création d'un instance historique relative au lancement immédiat du job

        return $this->redirectToRoute('app_jobCron_index', [], Response::HTTP_SEE_OTHER);

    }




    public function download(JobCron $jobCron,HistoriqueRepository $repository,KernelInterface $kernel,string $date){

        $historique = $repository->findByExampleField($jobCron,$date);

        return $this->file($kernel->getProjectDir().max($historique)->getPath());
    }



    public function actifdesactif(JobCron $jobCron,EntityManagerInterface $manager){
        if($jobCron->getActif()){
            $jobCron->setActif(false);
            $manager->persist($jobCron);
            $manager->flush();
        }
        else{
            $jobCron->setActif(true);
            $manager->persist($jobCron);
            $manager->flush();
        }
        return $this->redirectToRoute('app_jobCron_index', [], Response::HTTP_SEE_OTHER);
    }





    public function giveDate(JobCronRepository $repository){
        return new Response($repository->giveDateTime());
    }


    public function nextDate(JobCronRepository $repository){
        $jobCron = $repository->findElementById(13);
        $cron = new CronExpression($jobCron->getExpression());
//        return new Response($cron->getNextRunDate()->format('i G j n w'));
        echo date('i G j n w', strtotime('+1 minute'));
        echo $cron->getNextRunDate()->format('i G j n w');
        echo get_debug_type($cron->getNextRunDate()->format('i G j n w'));
        return new Response(date('i G j n w', strtotime('+1 minute'))==$cron->getNextRunDate()->format('i G j n w') ? 'yes':'no');

    }


}

