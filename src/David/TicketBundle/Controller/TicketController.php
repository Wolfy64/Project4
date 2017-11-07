<?php

namespace David\TicketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use David\TicketBundle\Entity\Reservation;

use David\TicketBundle\Form\ReservationType;
use Symfony\Component\HttpFoundation\Request;

class TicketController extends Controller
{
    public function indexAction(Request $request)
    {

        $reservation = new Reservation();

        $form = $this->createForm(ReservationType::class, $reservation);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if( $form->isValid() ){
            }
        }

        return $this->render('DavidTicketBundle:Default:index.html.twig', array(
            'form' => $form->createView()
        ));
    }
}