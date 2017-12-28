<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Reservation;
use App\Entity\Ticket;


class ReservationTest  extends TestCase
{
    public function testComputeCost()
    {
        $reservation = new Reservation();

        $ticket = $this->getMockBuilder(Ticket::class)
            ->setMethods(['getAmount'])
            ->getMock();
        $ticket->method('getAmount')->willReturn(5);
        
        // Add 3 tickets 
        $reservation->addTicket($ticket);
        $reservation->addTicket($ticket);
        $reservation->addTicket($ticket);

        $reservation->doCost();

        $this->assertSame(15, $reservation->getCost());
    }
}