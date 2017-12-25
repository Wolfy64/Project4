<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ReservationType;
use App\Entity\Reservation;
use App\Entity\Ticket;
use App\Repository\TicketRepository;
use Symfony\Component\Form\RequestHandlerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Session\Session;

class LouvreController extends Controller
{
    const SOLD_TIKETS_LIMIT = 1000;

    public function index(Request $request, Session $session)
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
                // ============ USE TicketService ============ 
                    // $ticketService->setTicket($ticket);
                    // $ticket->setPriceType($ticketService->doPriceType());
                    // $ticket->setAmount($ticketService->doAmount());
                    // ============ USE TicketService ============ 

                $ticket->setReservation($reservation);
            }

            $reservation->doCost();

            if ($this->ticketsLimit($reservation->getBookingDate()) != null) {

                $amount = $this->amount = $reservation->getCost() * 100; // !!! MODE BOURRIN !!!
                $session->set('amount', $amount);
                $session->set('reservation', $reservation);

                return $this->render('louvre/payment.html.twig', [
                    'amount' => $reservation->getCost() * 100,
                    'email' => $reservation->getEmail()
                ]);
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($ticket);
                $em->persist($this->reservation);
                // $em->flush();

                $this->addFlash(
                    'notice',
                    'Your form has been sent !'
                );
            }
        }

        return $this->render(
            'louvre/index.html.twig',[
                'form' => $form->createView()
            ]);
    }

    public function ticketsLimit($day = null)
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

    public function paymentProcess(Request $request, Session $session)
    {
        $reservation = $session->get('reservation');

        $stripe = array(
            "secret_key" => "sk_test_vR13pPT8iogxBJKWC1FOuDDj",
            "publishable_key" => "pk_test_zwG6fcavFG9NgdGA3aOaY2oZ"
        );

        \Stripe\Stripe::setApiKey($stripe['secret_key']);

        $token = $request->get('stripeToken');
        $email = $request->get('stripeEmail');

        $customer = \Stripe\Customer::create(array(
            'email' => $email,
            'source' => $token
        ));

        $charge = \Stripe\Charge::create(array(
            'customer' => $customer->id,
            'amount' => $reservation->getCost() * 100,
            'currency' => 'usd'
        ));

        $this->addFlash(
            'notice',
            'Payment ok !'
        );

        return $this->redirectToRoute('index');
    }
}