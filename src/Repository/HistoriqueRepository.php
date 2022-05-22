<?php

namespace BatchJobs\BatchJobsBundle\Repository;

use BatchJobs\BatchJobsBundle\Entity\Historique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Historique|null find($id, $lockMode = null, $lockVersion = null)
 * @method Historique|null findOneBy(array $criteria, array $orderBy = null)
 * @method Historique[]    findAll()
 * @method Historique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoriqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Historique::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Historique $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Historique $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Historique[] Returns an array of Historique objects
    //  */

    public function findByExampleField($value,$da)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.jobCronHist = :val and h.createdAt = :da')
            ->setParameter('val', $value)
            ->setParameter('da', $da)
            ->orderBy('h.id', 'ASC')

            ->getQuery()
            ->getResult()
        ;
    }



    public function findOneBySomeField($value): ?Historique
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.jobCronHist = :val ')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
            ->getMaxResult()
        ;
    }

    public function findByDate(\DateTime  $dateTime){

        return $this->createQueryBuilder('h')
            ->andWhere(' h.createdAt < :da')
            ->setParameter('da', $dateTime)
            ->orderBy('h.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

}
