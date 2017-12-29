<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as ReservationAssert;
use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 */
class Reservation
{
    const HALF_DAY_TIME = 14; // Time in 24H to start an 1/2 Day
    const SOLD_TIKETS_LIMIT = 1000;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
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
     * @ReservationAssert\PublicHoliday()
     * @Assert\GreaterThanOrEqual("today")
     */
    private $bookingDate;

    /**
     * @ORM\OneToMany(
     *      targetEntity="App\Entity\Ticket",
     *      mappedBy="reservation",
     *      cascade={"persist"}
     * )
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $tickets;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->orderDate = new \DateTime;
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
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
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
     * Get cost
     *
     * @return string
     */
    public function getCost()
    {
        return $this->cost;
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
     * Get bookingDate
     *
     * @return \DateTime
     */
    public function getBookingDate()
    {
        return $this->bookingDate;
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
     * Add ticket
     *
     * @param App\Entity\Ticket $ticket
     *
     * @return Reservation
     */
    public function addTicket($ticket)
    {
        $this->tickets->add($ticket);
    }

    /**
     * Remove ticket
     *
     * @param App\Entity\Ticket $ticket
     */
    public function removeTicket($ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Define price of $cost
     * 
     * @return $cost
     */
    public function doCost()
    {
        foreach ($this->tickets as $ticket) {
            $this->cost += $ticket->getAmount();
        }
    }

    /**
     * @Assert\IsTrue(message="Sorry, you can't get a Full-day ticket.")
     */
    public function isVisitTypeValid()
    {
        if ( $this->bookingDate->format('Y-m-d') === \date('Y-m-d') ){

            if (\date('H') >= self::HALF_DAY_TIME){

                return $this->visitType === "halfDay";
            }
        }

        return true;
    }
}
