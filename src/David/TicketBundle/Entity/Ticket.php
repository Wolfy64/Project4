<?php

namespace David\TicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="David\TicketBundle\Repository\TicketRepository")
 */
class Ticket
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
     * @var \DateTime
     * @ORM\Column(name="booking_date", type="datetime")
     * @Assert\DateTime(
     *      message = "The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $bookingDate;

    /**
     * @var string
     * @ORM\Column(name="price_type", type="string", length=255)
     * @Assert\Type(
     *      type = "string",
     *      message = "The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $priceType;

    /**
     * @var decimal
     * @ORM\Column(name="amount", type="decimal", precision=10, scale=0)
     * @Assert\Type(
     *      type = "decimal",
     *      message = "The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $amount;

    /**
     * @var bool
     * @ORM\Column(name="reducedPrice", type="boolean")
     * @Assert\Type(
     *      type = "bool",
     *      message = "The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $reducedPrice;

    /**
     * @ORM\OneToOne(targetEntity="David\TicketBundle\Entity\Guest", mappedBy="ticket")
     * @Assert\Valid()
     */
    private $guest;

    /**
     * @ORM\ManyToOne(targetEntity="David\TicketBundle\Entity\Reservation", inversedBy="tickets")
     * @Assert\Valid()
     */
    private $reservation;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set bookingDate
     *
     * @param string $bookingDate
     *
     * @return Ticket
     */
    public function setBookingDate($bookingDate)
    {
        $this->bookingDate = $bookingDate;

        return $this;
    }

    /**
     * Get bookingDate
     *
     * @return string
     */
    public function getBookingDate()
    {
        return $this->bookingDate;
    }

    /**
     * Set priceType
     *
     * @param string $priceType
     *
     * @return Ticket
     */
    public function setPriceType($priceType)
    {
        $this->priceType = $priceType;

        return $this;
    }

    /**
     * Get priceType
     *
     * @return string
     */
    public function getPriceType()
    {
        return $this->priceType;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return Ticket
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set guest
     *
     * @param \David\TicketBundle\Entity\Guest $guest
     *
     * @return Ticket
     */
    public function setGuest(\David\TicketBundle\Entity\Guest $guest = null)
    {
        $this->guest = $guest;

        return $this;
    }

    /**
     * Get guest
     *
     * @return \David\TicketBundle\Entity\Guest
     */
    public function getGuest()
    {
        return $this->guest;
    }

    /**
     * Set reservation
     *
     * @param \David\TicketBundle\Entity\Reservation $reservation
     *
     * @return Ticket
     */
    public function setReservation(\David\TicketBundle\Entity\Reservation $reservation = null)
    {
        $this->reservation = $reservation;

        return $this;
    }

    /**
     * Get reservation
     *
     * @return \David\TicketBundle\Entity\Reservation
     */
    public function getReservation()
    {
        return $this->reservation;
    }

    /**
     * Set reducedPrice
     *
     * @param boolean $reducedPrice
     *
     * @return Ticket
     */
    public function setReducedPrice($reducedPrice)
    {
        $this->reducedPrice = $reducedPrice;

        return $this;
    }

    /**
     * Get reducedPrice
     *
     * @return boolean
     */
    public function getReducedPrice()
    {
        return $this->reducedPrice;
    }
}
