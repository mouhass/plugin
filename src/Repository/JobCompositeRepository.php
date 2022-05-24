<?php

namespace BatchJobs\BatchJobsBundle\Repository;

use BatchJobs\BatchJobsBundle\Entity\JobComposite;

use BatchJobs\BatchJobsBundle\Entity\JobCompositeSearch;
use BatchJobs\BatchJobsBundle\Entity\JobCron;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method JobComposite|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobComposite|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobComposite[]    findAll()
 * @method JobComposite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobCompositeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobComposite::class);
    }

    public function verifyJobCron(string $command,EntityManagerInterface $em){
//        return $this->createQueryBuilder('job')
//            ->andWhere('command in job.listSousJobs')
//            ->setParameter('val', $command)
//            ->getQuery()
//            ->getOneOrNullResult();

        $qb = $em->createQueryBuilder()
            ->select('j')
            ->from('JobComposite','j')
            ->andWhere(':command in j.listSousJobs')
            ->setParameter('command',$command)
            ->getQuery();
        return $qb;
    }


    public function findByCode(string $name){
        return $this->createQueryBuilder('a')
            ->andWhere('a.code = :val')
            ->setParameter('val', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findVisibleQuery():QueryBuilder{
        return $this->createQueryBuilder('a');
    }
    public function findSpecific(JobCompositeSearch $search):Query
    {
        $query = $this->findVisibleQuery();
        if ($search->getCode() and $search->getCode() != "") {
            $query = $query->where('a.code = :code')
                ->setParameter('code', $search->getCode());
        }

        if ($search->getExpression() and $search->getExpression() != "") {
            $query = $query->where('a.expression = :expression')
                ->setParameter('expression', $search->getExpression());
        }

        if ($search->getActif() and $search->getActif() != "") {
            $query = $query->where('a.actif = :actif')
                ->setParameter('actif', $search->getActif());
        }
        if ($search->getName() and $search->getName() != "") {
            $query = $query->where('a.name like :name')
                ->setParameter('name', '%'.$search->getName().'%');
        }



        if($search->getCode()!="" and $search->getActif()!=""){
            $query->where('a.code = :code  and a.actif = :actif ')
                ->setParameter('code',$search->getCode())
                ->setParameter('actif',$search->getActif());
        }

        if($search->getExpression()!="" and $search->getActif()!=""){
            $query->where('a.expression = :expression  and a.actif = :actif ')
                ->setParameter('expression',$search->getExpression())
                ->setParameter('actif',$search->getActif());
        }

        if($search->getCode()!="" and $search->getName()!=""){
            $query->where('a.code = :code  and a.name like :name ')
                ->setParameter('code',$search->getCode())
                ->setParameter('name','%'.$search->getName().'%');
        }

        if($search->getExpression()!="" and $search->getName()!=""){
            $query->where('a.expression = :expression  and a.name like :name ')
                ->setParameter('expression',$search->getExpression())
                ->setParameter('name','%'.$search->getName().'%');
        }
        if($search->getExpression()!="" and $search->getCode()!=""){
            $query->where('a.expression = :expression  and a.code = :code ')
                ->setParameter('expression',$search->getExpression())
                ->setParameter('code', $search->getCode());
        }
        if($search->getActif()!="" and $search->getName()!=""){
            $query->where('a.actif = :actif  and a.name like :name ')
                ->setParameter('actif',$search->getActif())
                ->setParameter('name','%'.$search->getName().'%');
        }





        if($search->getCode()!="" and $search->getExpression()!="" and $search->getName()!=""){
            $query->where('a.code = :code and a.expression = :expression  and a.name like :name ')
                ->setParameter('code',$search->getCode())
                ->setParameter('expression',$search->getExpression())
                ->setParameter('name','%'.$search->getName().'%');
        }






        if($search->getActif()!="" and $search->getExpression()!="" and $search->getName()!=""){
            $query->where('a.actif = :actif and a.expression = :expression  and a.name like :name ')
                ->setParameter('actif',$search->getActif())
                ->setParameter('expression',$search->getExpression())
                ->setParameter('name','%'.$search->getName().'%');
        }

        if($search->getActif()!="" and $search->getCode()!="" and $search->getName()!=""){
            $query->where('a.actif = :actif and a.code = :code  and a.name like :name ')
                ->setParameter('actif',$search->getActif())
                ->setParameter('code',$search->getCode())
                ->setParameter('name','%'.$search->getName().'%');
        }



        if($search->getCode()!="" and $search->getExpression()!="" and $search->getActif()!=""){
            $query->where('a.code = :code and a.expression = :expression  and a.actif = :actif ')
                ->setParameter('code',$search->getCode())
                ->setParameter('expression',$search->getExpression())
                ->setParameter('actif',$search->getActif());
        }


        if($search->getCode()!="" and $search->getExpression()!="" and $search->getName()!="" and $search->getActif()>=0 and $search->getActif()<=1){
            $query->where('a.code = :code and a.expression = :expression and a.actif = :actif and a.name like :name')
                ->setParameter('code',$search->getCode())
                ->setParameter('expression',$search->getExpression())
                ->setParameter('name','%'.$search->getName().'%')
                ->setParameter('actif',$search->getActif());
        }



        return $query->getQuery();

    }


    public function calculateJobCompErr(){
        return $this->createQueryBuilder('a')
            ->select('count(a.id)')
            ->andWhere('a.state = :val')
            ->setParameter('val', "erreur")
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function calculateJobCompEnCours(){
        return $this->createQueryBuilder('a')
            ->select('count(a.id)')
            ->andWhere('a.state = :val')
            ->setParameter('val', "en cours")
            ->getQuery()
            ->getSingleScalarResult();
    }

}
