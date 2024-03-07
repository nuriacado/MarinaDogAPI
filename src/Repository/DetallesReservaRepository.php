<?php

namespace App\Repository;

use App\Entity\DetallesReserva;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DetallesReserva>
 *
 * @method DetallesReserva|null find($id, $lockMode = null, $lockVersion = null)
 * @method DetallesReserva|null findOneBy(array $criteria, array $orderBy = null)
 * @method DetallesReserva[]    findAll()
 * @method DetallesReserva[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DetallesReservaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DetallesReserva::class);
    }

    //    /**
    //     * @return DetallesReserva[] Returns an array of DetallesReserva objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?DetallesReserva
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
