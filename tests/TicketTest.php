<?php

namespace App\Test\Entity;

use Symfony\Component\Form\Tests\AbstractDivLayoutTest;
use PHPUnit\Framework\TestCase;
use App\Entity\Ticket;
use App\Entity\Guest;


class TicketTest extends TestCase
{
    /**
     * @dataProvider dateOfBirthForPriceType
     */
    public function testDefinePriceType($dateOfBirth, $expectedPriceType)
    {
        $ticket = new Ticket();

        $guest = $this->getMockBuilder(Guest::class)
            ->setMethods(['getDateOfBirth'])
            ->getMock();
        $guest->method('getDateOfBirth')->willReturn($dateOfBirth);

        $ticket->setGuest($guest);
        $ticket->doPriceType();

        $this->assertSame($expectedPriceType, $ticket->getPriceType());
    }

    public function dateOfBirthForPriceType()
    {
        return[
            [new \DateTime('-3 year' ), Ticket::TODDLER],
            [new \DateTime('-4 year' ), Ticket::TODDLER],
            [new \DateTime('-5 year' ), Ticket::CHILD  ],
            [new \DateTime('-11 year'), Ticket::CHILD  ],
            [new \DateTime('-12 year'), Ticket::NORMAL ],
            [new \DateTime('-13 year'), Ticket::NORMAL ],
            [new \DateTime('-59 year'), Ticket::NORMAL ],
            [new \DateTime('-60 year'), Ticket::ELDERLY],
            [new \DateTime('-61 year'), Ticket::ELDERLY]
        ];
    }
}
