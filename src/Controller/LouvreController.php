<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Reservation;
use App\Entity\Ticket;
use App\Form\ReservationType;
use App\Repository\TicketRepository;
use Symfony\Component\Config\Definition\Exception\Exception;
use App\Services\Compute;
use App\Services\Stripe;
use App\Services\Message;

class LouvreController extends Controller
{
    public function index(Request $request, Session $session, Compute $compute, TicketRepository $numberTickets)
    {
        $reservation = new Reservation();
        $ticket      = new Ticket();
        $reservation->addTicket($ticket);

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $compute->setReservation($reservation);
            $compute->price();

            if (!$compute->isCostValid()){
                $this->addFlash('notice', $reservation->getCost() . '€ is an insufficient amount to order online.');
                return $this->redirectToRoute('index');
            }

            $hasTickets = $compute->hasTickets($numberTickets);

            if ($hasTickets === 'yes'){
                $session->set('reservation', $reservation);
                return $this->render('louvre/payment.html.twig', [
                    'amount'      => $reservation->getCost() * 100,
                    'email'       => $reservation->getEmail(),
                    'countTicket' => count($reservation->getTickets()),
                    'bookingDate' => $reservation->getBookingDate()->format('l d F Y'),
                    'tickets'     => $reservation->getTickets()
                ]);
            }elseif ($hasTickets === 'none') {
                $this->addFlash('notice', 'Sorry, tickets for The Louvre Museum are sold out !');
            }else {
                $this->addFlash('notice', 'Sorry, only ' . $hasTickets . ' tickets left !');
            }
        }

        return $this->render('louvre/index.html.twig',[
                'form' => $form->createView()
            ]);
    }

    public function paymentProcess(Request $request, Session $session, \Swift_Mailer $mailer, Stripe $stripe)
    {
        $reservation = $session->get('reservation');
        $stripe->setReservation($reservation);

        $email = $request->get('stripeEmail');
        $token = $request->get('stripeToken');
        $paymentIsValid = $stripe->process($email, $token);

        // Insert data in database
        if ($paymentIsValid){
            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();
            return $this->redirectToRoute('mails');
        }

        $this->addFlash('notice', $stripe->getErrorMsg());
        return $this->render('louvre/payment.html.twig', [
            'amount'      => $reservation->getCost() * 100,
            'email'       => $reservation->getEmail(),
            'countTicket' => count($reservation->getTickets()),
            'bookingDate' => $reservation->getBookingDate()->format('l d F Y'),
            'tickets'     => $reservation->getTickets()
        ]);
    }

    public function mails(Session $session, \Swift_Mailer $mailer)
    {
        $reservation = $session->get('reservation');

        $message = (new \Swift_Message('Order Receipt'))
            ->setFrom('ledavid64@gmail.com')
            ->setTo($reservation->getEmail())
            ->setBody($this->renderView('emails/order.html.twig',[
                    'bookingDate' => $reservation->getBookingDate()->format('l d F Y'),
                    'tickets'     => $reservation->getTickets(),
                    'amount'      => $reservation->getCost(),
                    'code'        => \substr(\sha1($reservation->getEmail()), 0, 8)
                ]),
                'text/html'
        );

        $mailer->send($message);

        $this->addFlash('notice','Your order receipt has been sent to your email !');

        return $this->redirectToRoute('index');
    }
}