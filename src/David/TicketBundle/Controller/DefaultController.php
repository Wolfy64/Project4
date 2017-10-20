<?php

namespace David\TicketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DavidTicketBundle:Default:index.html.twig');
    }
}
