<?php

namespace App\Test\Services;

use PHPUnit\Framework\TestCase;
use App\Entity\Ticket;
use App\Entity\Guest;
use App\Services\Compute;
use App\Repository\TicketRepository;
use App\Entity\Reservation;

class ComputeTest extends TestCase
{
    /**
     * @dataProvider dateOfBirthForPriceType
     */
    public function testDoPriceType($dateOfBirth, $expectedPriceType)
    {
        $TicketRepository = $this->getMockBuilder(TicketRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $compute = new Compute($TicketRepository);

        $guest = new Guest();
        $guest->setDateOfBirth($dateOfBirth);
        
        $ticket = new Ticket();
        $ticket->setGuest($guest);

        $compute->doPriceType($ticket);

        $this->assertSame($expectedPriceType, $ticket->getPriceType());
    }

    /**
     * @dataProvider priceTypeForAmount
     */
    public function testDoAmount($priceType, $expectedAmount)
    {
        $TicketRepository = $this->getMockBuilder(TicketRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $compute = new Compute($TicketRepository);

        $ticket = new Ticket();
        $ticket->setPriceType($priceType);

        $compute->doAmount($ticket);

        $this->assertSame($expectedAmount, $ticket->getAmount());
    }

    public function testDoAmountWithDiscount()
    {
        $TicketRepository = $this->getMockBuilder(TicketRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $compute = new Compute($TicketRepository);

        $ticket = new Ticket();
        $ticket->setPriceType('normal');
        $ticket->setReducedPrice(true);

        $compute->doAmount($ticket);

        $this->assertSame(6, $ticket->getAmount());
    }

    public function testExceptionAmount()
    {
        $TicketRepository = $this->getMockBuilder(TicketRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $compute = new Compute($TicketRepository);

        $ticket = new Ticket();
        $ticket->setPriceType('other');
        $this->expectException('Error');

        $compute->doAmount($ticket);
    }

    public function testdoCost()
    {
        $TicketRepository = $this->getMockBuilder(TicketRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $compute = new Compute($TicketRepository);

        $ticket = new Ticket();
        $ticket->setAmount(5);

        $reservation = new Reservation();
        $compute->setReservation($reservation);
        
        // Add 3 tickets 
        $reservation->addTicket($ticket);
        $reservation->addTicket($ticket);
        $reservation->addTicket($ticket);

        $compute->doCost();

        $this->assertSame(15, $reservation->getCost());
    }

    public function dateOfBirthForPriceType()
    {
        return [
            [new \DateTime('-3 year'),  Compute::TODDLER],
            [new \DateTime('-4 year'),  Compute::CHILD],
            [new \DateTime('-5 year'),  Compute::CHILD],
            [new \DateTime('-11 year'), Compute::CHILD],
            [new \DateTime('-12 year'), Compute::NORMAL],
            [new \DateTime('-13 year'), Compute::NORMAL],
            [new \DateTime('-59 year'), Compute::NORMAL],
            [new \DateTime('-60 year'), Compute::ELDERLY],
            [new \DateTime('-61 year'), Compute::ELDERLY]
        ];
    }

    public function priceTypeForAmount()
    {
        return [
            ['toddler', 0],
            ['child',   8],
            ['normal', 16],
            ['elderly',12]
        ];
    }
}