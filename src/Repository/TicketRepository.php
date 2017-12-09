<?php

namespace App\Repository;

use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TicketRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    /**
     * Count how many tickets are they in database for a specific day
     * 
     * @param $date
     * @return $numberOfTicketsDay[]
     */
    public function countTicketByDay($date)
    {
        return \count( $this->createQueryBuilder('t')
            ->addSelect('r')
            ->join('t.reservation','r')
            ->where('r.bookingDate = :date')
            ->setParameter(':date',$date)
            ->getQuery()
            ->getResult())
            // ->getScalarResult()
        ;
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('t')
            ->where('t.something = :value')->setParameter('value', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
