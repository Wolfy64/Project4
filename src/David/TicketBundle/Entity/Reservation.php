<?php

namespace David\TicketBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\DateTime;


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
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Your email cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Email(
     *      checkMX = true,
     *      message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(name="visit_type", type="string")
     * @Assert\NotBlank()
     * @Assert\Choice(
     *      {"fullDay", "halfDay"},
     *      strict = true     
     *  )
     */
    private $visitType;

    /**
     * @var decimal
     * @ORM\Column(name="cost", type="decimal", precision=10, scale=0)
     * Assert\NotBlank()
     * Assert\Type(
     *      type = "numeric",
     *      message = "The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $cost;

    /**
     * @var \DateTime
     * @ORM\Column(name="date", type="datetime")
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    private $orderDate;

    /**
     * @var \DateTime
     * @ORM\Column(name="booking_date", type="datetime")
     * @Assert\NotBlank()
     * @Assert\DateTime(
     *      message = "The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $bookingDate;

    /**
     * @ORM\OneToMany(
     *      targetEntity="David\TicketBundle\Entity\Ticket",
     *      mappedBy="reservation",
     *      cascade={"persist"}
     * )
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    private $tickets;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->orderDate = new \DateTime();
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
     * Add ticket
     *
     * @param \David\TicketBundle\Entity\Ticket $ticket
     *
     * @return Reservation
     */
    public function addTicket(\David\TicketBundle\Entity\Ticket $ticket)
    {
        $this->tickets->add($ticket);
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

    /**
     * Set orderDate
     *
     * @param \DateTime $orderDate
     *
     * @return Reservation
     */
    public function setOrderDate($orderDate)
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    /**
     * Get orderDate
     *
     * @return \DateTime
     */
    public function getOrderDate()
    {
        return $this->orderDate;
    }

    /**
     * Set bookingDate
     *
     * @param \DateTime $bookingDate
     *
     * @return Reservation
     */
    public function setBookingDate($bookingDate)
    {
        $this->bookingDate = $bookingDate;

        return $this;
    }

    /**
     * Get bookingDate
     *
     * @return \DateTime
     */
    public function getBookingDate()
    {
        return $this->bookingDate;
    }

}
