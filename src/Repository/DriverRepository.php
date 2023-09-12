<?php

namespace App\Repository;

use App\Entity\Driver;
use App\Entity\Trip;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Driver>
 *
 * @method Driver|null find($id, $lockMode = null, $lockVersion = null)
 * @method Driver|null findOneBy(array $criteria, array $orderBy = null)
 * @method Driver[]    findAll()
 * @method Driver[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DriverRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Driver::class);
    }

    /**
     * @param string $license
     * @param DateTime $rangeA
     * @param DateTime $rangeB
     * @return Driver[]
     */
    public function findBySameLicense($license, $rangeA, $rangeB)
    {
        return $this->createQueryBuilder('d')
            ->join(Trip::class, 't', Join::WITH, 't.Vehicle = d.id')
            ->where(sprintf("d.License='%s'", $license))
            ->andWhere(sprintf("not (t.Date >='%s' and t.Date <'%s')",
                $rangeA->format('Y-m-d'),
                $rangeB->format('Y-m-d')

            ))
            ->getQuery()
            ->getResult();
    }
}
