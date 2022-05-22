<?php

namespace BatchJobs\BatchJobsBundle\Repository;


use BatchJobs\BatchJobsBundle\Entity\Admin;
use BatchJobs\BatchJobsBundle\Entity\JobCron;
use BatchJobs\BatchJobsBundle\Entity\JobCronSearch;
use Cron\CronExpression;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method JobCron|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobCron|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobCron[]    findAll()
 * @method JobCron[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobCronRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobCron::class);
    }

    public function add(JobCron $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findElementById( string $id){
        return $this->createQueryBuilder('a')
            ->andWhere('a.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(JobCron $jobCron, bool $flush = true): void
    {
        $this->_em->remove($jobCron);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function commandPossesses(string $command){
        return $this->createQueryBuilder('a')
            ->andWhere('a.scriptExec = :val')
            ->setParameter('val', $command)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllVisible():Query
    {
        return $this->findVisibleQuery()->getQuery();

    }
    public function findVisibleQuery():QueryBuilder{
        return $this->createQueryBuilder('p');


    }

    public function findSpecific(JobCronSearch $search):Query{
         $query = $this->findVisibleQuery();
         if($search->getCode() and $search->getCode()!=""){
                $query->where('p.code = :code')
                    ->setParameter('code',$search->getCode());
         }
         if($search->getCommand() and $search->getCommand()!=""){
             $query->where('p.scriptExec = :command')
                 ->setParameter('command',$search->getCommand());
         }

        if($search->getActif() and $search->getActif()!=""){
            $query->where('p.actif = :actif')
                ->setParameter('actif',$search->getActif());
        }

        if($search->getName() and $search->getName()!=""){
            $query->where('p.name like :name')
                ->setParameter('name','%'.$search->getName().'%');
        }





        if($search->getCode()!="" and $search->getActif()!=""){
            $query->where('p.code = :code  and p.actif = :actif ')
                ->setParameter('code',$search->getCode())
                ->setParameter('actif',$search->getActif());
        }

        if($search->getCommand()!="" and $search->getActif()!=""){
            $query->where('p.scriptExec = :command  and p.actif = :actif ')
                ->setParameter('command',$search->getCommand())
                ->setParameter('actif',$search->getActif());
        }

        if($search->getCode()!="" and $search->getName()!=""){
            $query->where('p.code = :code  and p.name like :name ')
                ->setParameter('code',$search->getCode())
                ->setParameter('name','%'.$search->getName().'%');
        }

        if($search->getCommand()!="" and $search->getName()!=""){
            $query->where('p.scriptExec = :command  and p.name like :name ')
                ->setParameter('command',$search->getCommand())
                ->setParameter('name','%'.$search->getName().'%');
        }
        if($search->getCommand()!="" and $search->getCode()!=""){
            $query->where('p.scriptExec = :command  and p.code = :code ')
                ->setParameter('command',$search->getCommand())
                ->setParameter('code', $search->getCode());
        }
        if($search->getActif()!="" and $search->getName()!=""){
            $query->where('p.actif = :actif  and p.name like :name ')
                ->setParameter('actif',$search->getActif())
                ->setParameter('name','%'.$search->getName().'%');
        }





        if($search->getCode()!="" and $search->getCommand()!="" and $search->getName()!=""){
            $query->where('p.code = :code and p.scriptExec = :command  and p.name like :name ')
                ->setParameter('code',$search->getCode())
                ->setParameter('command',$search->getCommand())
                ->setParameter('name','%'.$search->getName().'%');
        }
        if($search->getCode()!="" and $search->getCommand()!="" and $search->getActif()!=""){
             $query->where('p.code = :code and p.scriptExec = :command  and p.actif = :actif ')
                 ->setParameter('code',$search->getCode())
                 ->setParameter('command',$search->getCommand())
                 ->setParameter('actif',$search->getActif());
         }


        if($search->getCode()!="" and $search->getCommand()!="" and $search->getName()!="" and $search->getActif()>=0 and $search->getActif()<=1){
            $query->where('p.code = :code and p.scriptExec = :command  and p.name like :name ')
                ->setParameter('code',$search->getCode())
                ->setParameter('command',$search->getCommand())
                ->setParameter('name','%'.$search->getName().'%')
                ->setParameter('actif',$search->getActif());
        }



         return  $query->getQuery();
    }











    public function getNextDate(){
        $cron = new CronExpression('0 0 * * *');
        $cron->isDue();
        $cron->getNextRunDate()->format('Y-m-d H:i:s');
        return $cron;
    }

    public function giveDateTime()
    {
        return date("i G d m y");
    }

    public function calculateJobCronErr(){
        return $this->createQueryBuilder('a')
            ->select('count(a.id)')
            ->andWhere('a.state = :val')
            ->setParameter('val', "erreur")
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function calculateJobCronEnCours(){
        return $this->createQueryBuilder('a')
            ->select('count(a.id)')
            ->andWhere('a.state = :val')
            ->setParameter('val', "en cours")
            ->getQuery()
            ->getSingleScalarResult();
    }


}
