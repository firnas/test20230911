<?php

namespace App\Repository;

use App\Entity\Trip;
use App\Entity\Vehicle;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vehicle>
 *
 * @method Vehicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicle[]    findAll()
 * @method Vehicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehicleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }

    /**
     * @param DateTime $rangeA
     * @param DateTime $rangeB
     * @return Vehicle[]
     */
    public function findByNotReserved($rangeA, $rangeB)
    {
         return $this->createQueryBuilder('v')
            ->join(Trip::class, 't', Join::WITH, 't.Vehicle = v.id')
            ->where(sprintf("not (t.Date >='%s' and t.Date <'%s')",
                $rangeA->format('Y-m-d'),
                $rangeB->format('Y-m-d')
            ))
            ->getQuery()
            ->getResult();
    }
}
