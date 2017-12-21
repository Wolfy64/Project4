<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ReservationType;
use App\Entity\Reservation;
use App\Entity\Ticket;
use App\Repository\TicketRepository;

class LouvreController extends Controller
{
    const SOLD_TIKETS_LIMIT = 1000;

    public function ticket(Request $request)
    {
        $reservation = new Reservation();
        $ticket = new Ticket();

        $reservation->addTicket($ticket);

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($reservation->getTickets() as $ticket) {
                $ticket->doPriceType();
                $ticket->doAmount();
                $ticket->setReservation($reservation);
            }

            $reservation->doCost();
            $numberOfTickets = $this->ticketProcess( $reservation->getBookingDate() );

            if ($numberOfTickets != null){
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($ticket);
                $em->persist($reservation);
                $em->flush();
                
                $this->addFlash(
                    'notice',
                    'Your form has been sent !'
                );
            }
        }

        return $this->render('louvre/index.html.twig', array(
            'form' => $form->createView(),
            'priceType' => $ticket->getPriceType(),
            'amount' => $ticket->getAmount(),
            'cost' => $reservation->getCost(),
            'billet' => '$this->ticketProcess()'
        ));
    }

    public function ticketProcess($day = null)
    {
        if ($day === null){
            $dateTime = new \DateTime();
            $day = $dateTime->format('Y-m-d');
        }

        $numberOfTicketsByDay = 
            $this->getDoctrine()
                ->getRepository(Ticket::class)
                ->countTicketByDay($day)
        ;

        if ( $numberOfTicketsByDay >= self::SOLD_TIKETS_LIMIT){
            $this->addFlash(
                'notice',
                'Sorry, tickets for The Louvre Museum are sold out !'
            );
        }else{
           return  $libre = self::SOLD_TIKETS_LIMIT - $numberOfTicketsByDay;
        }
    }
}