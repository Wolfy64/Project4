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

        $reservation->addTicket($ticket);

        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            // Défini certains attributs en interne
            foreach ($reservation->getTickets() as $ticket) {
                $ticket->setPriceType($ticket
                                        ->getGuest()
                                        ->getDateOfBirth());
                $ticket->setAmount( $ticket->getPriceType() );
                $reservation->setCost($ticket->getAmount() );
            }
            \dump($ticket);
            // Persiste chaque objet avant d'envoyé le tout en bdd via Doctrine
            $em = $this->getDoctrine()->getManager();
            $em->persist($ticket);
            $em->persist($reservation);
            $em->flush();
            var_dump( 'Data send !!' );
        }

        return $this->render('DavidTicketBundle:Ticket:index.html.twig', array(
            'form' => $form->createView(),
            'priceType' => $ticket->getPriceType(),
            'amount'    => $ticket->getAmount(),
            'cost' => $reservation->getCost(),
        ));
    }
}