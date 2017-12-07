<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Form\ReservationType;
use App\Entity\Reservation;
use App\Entity\Ticket;

class LouvreController extends Controller
{
    public function ticket(Request $request)
    {
        // $this->dateProcess();

        $reservation = new Reservation();
        $ticket = new Ticket();

        $reservation->addTicket($ticket);

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($reservation->getTickets() as $ticket) {
                $ticket->doPriceType();
                $ticket->doAmount();
            }

            $reservation->doCost();

            $em = $this->getDoctrine()->getManager();
            $em->persist($ticket);
            $em->persist($reservation);
            // $em->flush();
            $this->addFlash(
                'notice',
                'Your form has been sent !'
            );
        }

        return $this->render('louvre/index.html.twig', array(
            'form' => $form->createView(),
            'priceType' => $ticket->getPriceType(),
            'amount' => $ticket->getAmount(),
            'cost' => $reservation->getCost(),
        ));
    }

    public function ticketProcess()
    {

    }
}