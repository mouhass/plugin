<?php


namespace BatchJobs\BatchJobsBundle\Command;


use BatchJobs\BatchJobsBundle\Controller\HistoriqueController;
use BatchJobs\BatchJobsBundle\Repository\AdminRepository;
use BatchJobs\BatchJobsBundle\Repository\HistoriqueRepository;
use BatchJobs\BatchJobsBundle\Repository\JobCompositeRepository;
use BatchJobs\BatchJobsBundle\Repository\JobCronRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class PurgeHistoriqueCommand extends Command
{
  protected  static $defaultName= "app:purgeHistory";

    private $mailer;
    private $logger;
    private $kernel;
    protected $manager;
    private $repository;
    private $managerRegistry;
    private $historiqueRepository;
    public function __construct(string $name = null,MailerInterface $mailer,LoggerInterface $logger,KernelInterface  $kernel,EntityManagerInterface $manager,JobCronRepository $repository, ManagerRegistry $managerRegistry,HistoriqueRepository $historiqueRepository)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->kernel = $kernel;
        $this->manager = $manager;
        $this->repository = $repository;
        $this->managerRegistry = $managerRegistry;
        $this->historiqueRepository = $historiqueRepository;
        parent::__construct($name,$manager);
    }

    public function configure(){
        $this
            ->addArgument('Related_job', InputArgument::OPTIONAL, 'Whitch one this command is related to?')
            ->addArgument('code_job_composite',InputArgument::OPTIONAL, 'si la commande est lancée à partir de job composite?')
            ->addArgument('dernier_Sous_Job',InputArgument::OPTIONAL,'si c est loe dernier sous job ?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {   //******
        $structCommand = new StructCommand($this->manager,$this->managerRegistry,$this->repository,$this->mailer,$this->kernel);
        //************
        try {
            //********
            $jobCron = $this->repository->findElementById($input->getArgument('Related_job'));
            //*********

            //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
            //%%%%%%%%%%%%%%ici commence le traitement
            //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

            // $output->writeln("wow wow");
            // $myfile = fopen("webdictionary.txt", "r");
            $output->writeln("wow wow");
            $log = "command name: app:exportAdmin  state: success  execution date" . ' - ' . date("F j, Y, G:i") . PHP_EOL .
                "-------------------------" . PHP_EOL;
            if ($input->getArgument('code_job_composite') == "0") {
                file_put_contents($this->kernel->getProjectDir() . '/var/log/EffaceHistorique_succes_' . date("y-m-d-G-i-s") . '.log', $log, FILE_APPEND);
            } else {
                file_put_contents($this->kernel->getProjectDir() . '/var/log/EffaceHistorique_succes_' . $input->getArgument('code_job_composite') . date("y-m-d-G-i-s") . '.log', $log, FILE_APPEND);
                //file_put_contents($this->kernel->getProjectDir() .  $input->getArgument('code_job_composite')  . '.log', $log, FILE_APPEND);
            }
            $date = new \DateTime();
           $date->format('Y-m-d H:i:s');
            $list = $this->historiqueRepository->findByDate($date);
            $histController = new HistoriqueController();
                $request = new Request();

           for($i=0;$i<count($list);$i++){
               $this->manager->remove($list[$i]);
               $this->manager->flush();
           }

                //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                //%%%%%%%%%% ici se termine le traitement
                //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%


                //une partie de création d'historique
                $structCommand->ajoutHistoriqueSucces($input,$jobCron, '/var/log/EffaceHistorique_succes_');
                //une partie de changement d'état
                $jobCron->setState('Succès');
                $this->manager->persist($jobCron);
                $this->manager->flush();

                $structCommand->modifierEtatJobCronSucces($jobCron);

                if ($input->getArgument('dernier_Sous_Job') == "1") {
                    $jobCompositeRepo = new JobCompositeRepository($this->managerRegistry);
                    $jobComposite = $jobCompositeRepo->findByCode(strval($input->getArgument('code_job_composite')));
                    $structCommand->modifierEtatJobCompositeSucces($jobComposite);

                }
            }

        catch(\Exception $exception){
            $jobCron = $this->repository->findElementById($input->getArgument('Related_job'));
            //une partie de changement d'état dans le cas d'une erreur dans l'exec
            $structCommand->modifierEtatJobCronError($jobCron);
            $log = "command name: app:saywow  state: error  error date" . ' - ' . date("F j, Y, G:i") . PHP_EOL .
                "error description : ".$exception.
                "-------------------------" . PHP_EOL;
            file_put_contents($this->kernel->getProjectDir() . '/var/log/EffaceHistorique_error' . date("y-m-d-G-i-s") . '.log', $log, FILE_APPEND);
            //une partie d'ajout d'historique dans le cas d'erreur
            $structCommand->ajoutHistoriqueError($input,$jobCron, 'EffaceHistorique');
            if($input->getArgument('code_job_composite')=="0") {
                $structCommand->EnvoyerEmailErrorCron($jobCron);
            }
            if($input->getArgument('code_job_composite')!="0") {
                $jobCompositeRepo = new JobCompositeRepository($this->managerRegistry);
                $jobComposite = $jobCompositeRepo->findByCode($input->getArgument('code_job_composite'));
                $structCommand->EnvoyerEmailErrorComposite($jobComposite,$jobCron);
                $structCommand->modifierEtatJobCompositeError($input,$jobComposite);
            }
        }
        return(1);
    }

}
