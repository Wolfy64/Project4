<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Reservation;
use App\Entity\Ticket;
use App\Form\ReservationType;
use App\Services\Compute;
use App\Services\Stripe;
use App\Services\Mail;

class LouvreController extends Controller
{
    public function index(Request $request, Session $session, Compute $compute)
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
                $this->addFlash('notice', $compute->getMessage());
                return $this->redirectToRoute('index');
            }

            if ($compute->hasTickets() === true){
                $session->set('reservation', $reservation);
                return $this->render('louvre/payment.html.twig', [
                    'amount'      => $reservation->getCost() * 100,
                    'email'       => $reservation->getEmail(),
                    'countTicket' => count($reservation->getTickets()),
                    'bookingDate' => $reservation->getBookingDate()->format('d/m/Y'),
                    'tickets'     => $reservation->getTickets()
                ]);
            }

            $this->addFlash('notice', $compute->getMessage());
        }
        return $this->render('louvre/index.html.twig',[
                'form' => $form->createView()
            ]);
    }

    public function paymentProcess(Request $request, Session $session, Stripe $stripe, Mail $mail)
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

            $mail->setReservation($reservation);
            $mail->send();
            $this->addFlash('notice', 'Your order receipt has been sent to your email !');
            return $this->redirectToRoute('index');
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
}