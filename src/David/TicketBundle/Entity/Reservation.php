<?php

namespace David\TicketBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity(repositoryClass="David\TicketBundle\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var bool
     *
     * @ORM\Column(name="visit_type", type="boolean")
     */
    private $visitType;

    /**
     * @var string
     *
     * @ORM\Column(name="cost", type="decimal", precision=10, scale=0)
     */
    private $cost;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity="David\TicketBundle\Entity\Ticket", mappedBy="reservation")
     */
    private $tickets;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->date = new \DateTime();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Reservation
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set visitType
     *
     * @param boolean $visitType
     *
     * @return Reservation
     */
    public function setVisitType($visitType)
    {
        $this->visitType = $visitType;

        return $this;
    }

    /**
     * Get visitType
     *
     * @return boolean
     */
    public function getVisitType()
    {
        return $this->visitType;
    }

    /**
     * Set cost
     *
     * @param string $cost
     *
     * @return Reservation
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return string
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Reservation
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Add ticket
     *
     * @param \David\TicketBundle\Entity\Ticket $ticket
     *
     * @return Reservation
     */
    public function addTicket(\David\TicketBundle\Entity\Ticket $ticket)
    {
        $this->tickets[] = $ticket;

        $ticket->setReservation($this);

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param \David\TicketBundle\Entity\Ticket $ticket
     */
    public function removeTicket(\David\TicketBundle\Entity\Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }

}
