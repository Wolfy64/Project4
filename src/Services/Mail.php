<?php

namespace App\Services;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Twig\Environment;


class Mail
{
    const EMAIL = 'louvre@project4.dewulfdavid.com';

    private $reservation;
    private $twig;
    private $mailer;

    public function __construct(Environment $twig, \Swift_Mailer $mailer)
    {
        $this->twig = $twig;
        $this->mailer =$mailer;
    }
    public function setReservation($reservation)
    {
        $this->reservation =  $reservation;
    }

    public function send()
    {
        $message = (new \Swift_Message('Louvre'))
            ->setFrom(self::EMAIL)
            ->setTo($this->reservation->getEmail())
            ->setBody(
                $this->twig->render('emails/order.html.twig', [
                    'bookingDate' => $this->reservation->getBookingDate()->format('d/m/Y'),
                    'tickets'     => $this->reservation->getTickets(),
                    'amount'      => $this->reservation->getCost(),
                    'code'        => \substr(\sha1($this->reservation->getEmail()), 0, 8)
                ]),
                'text/html'
            );

        $this->mailer->send($message);
    }
}