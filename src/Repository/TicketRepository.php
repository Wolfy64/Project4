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
        return $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->join('t.reservation','r')
            ->where('r.bookingDate = :date')
            ->setParameter(':date',$date)
            ->getQuery()
            ->getSingleScalarResult()
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
