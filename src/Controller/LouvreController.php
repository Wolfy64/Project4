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

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function index(Request $request, Session $session)
    {
        $reservation = new Reservation();
        $ticket      = new Ticket();

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

                $session->set('reservation', $reservation);

                return $this->render('louvre/payment.html.twig', [
                    'amount'      => $reservation->getCost() * 100,
                    'email'       => $reservation->getEmail(),
                    'countTicket' => count($reservation->getOrderDate()),
                    'bookingDate' => $reservation->getBookingDate()->format('l d F Y'),
                    'tickets'     => $reservation->getTickets()
                ]);
            }
        }

        return $this->render('louvre/index.html.twig',[
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
            $this->addFlash('notice',
                'Sorry, tickets for The Louvre Museum are sold out !'
            );
        }else{
           return  $libre = self::SOLD_TIKETS_LIMIT - $numberOfTicketsByDay;
        }
    }

    public function paymentProcess(Request $request, Session $session)
    {
        $reservation = $session->get('reservation');

        $stripe = [
            "secret_key"      => "sk_test_vR13pPT8iogxBJKWC1FOuDDj",
            "publishable_key" => "pk_test_zwG6fcavFG9NgdGA3aOaY2oZ"
        ];

        \Stripe\Stripe::setApiKey($stripe['secret_key']);

        $customer = \Stripe\Customer::create([
            'email'  => $request->get('stripeEmail'),
            'source' => $request->get('stripeToken')
        ]);

        $charge = \Stripe\Charge::create([
            'customer' => $customer->id,
            'amount'   => $reservation->getCost() * 100,
            'currency' => 'usd'
        ]);

        $em = $this->getDoctrine()->getManager();
        $em->persist($reservation);
        $em->flush();

        return $this->redirectToRoute('mails');
    }

    public function mails(Session $session)
    {
        $reservation = $session->get('reservation');

        $message = (new \Swift_Message('Order Receipt'))
            ->setFrom('ledavid64@gmail.com')
            ->setTo('symfonytestmail@armyspy.com')
            ->setBody($this->renderView('emails/order.html.twig',[
                    'bookingDate' => $reservation->getBookingDate()->format('l d F Y'),
                    'tickets'     => $reservation->getTickets(),
                    'amount'      => $reservation->getCost(),
                    'code'        => \sha1($reservation->getemail())]),
                'text/html'
        );

        $this->mailer->send($message);

        $this->addFlash('notice',
            'Your order receipt has been sent at your email !'
        );

        return $this->redirectToRoute('index');
    }
}