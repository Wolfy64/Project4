<?php

namespace David\TicketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use David\TicketBundle\Entity\Reservation;
use David\TicketBundle\Entity\Ticket;
use David\TicketBundle\Entity\Guest;

use David\TicketBundle\Form\ReservationType;
use Symfony\Component\HttpFoundation\Request;

class TicketController extends Controller
{
    public function indexAction(Request $request)
    {

        $reservation = new Reservation();
        $ticket      = new Ticket();
        $guest       = new Guest();

        $guest->setTicket($ticket);
        $ticket->setGuest($guest);
        $reservation->getTickets()->add($ticket);
        
        $form = $this->createForm(ReservationType::class, $reservation);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if( $form->isValid() ){
                $reservation->setCost(20);
                $ticket->setPriceType('normal');
                $ticket->setAmount(16);
                $em = $this->getDoctrine()->getManager();
                $em->persist($guest);
                $em->persist($ticket);
                $em->persist($reservation);
                $em->flush();
            }
        }

        return $this->render('DavidTicketBundle:Default:index.html.twig', array(
            'form' => $form->createView()
        ));
    }
}