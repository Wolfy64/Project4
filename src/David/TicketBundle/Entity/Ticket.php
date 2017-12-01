<?php

namespace David\TicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use David\TicketBundle\Entity\Guest;
use David\TicketBundle\Entity\Reservation;

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
     * @var string
     * @ORM\Column(name="price_type", type="string", length=255)
     * Assert\NotBlank()
     * Assert\Type(
     *      type = "string",
     *      message = "The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $priceType;

    /**
     * @var decimal
     * @ORM\Column(name="amount", type="decimal", precision=10, scale=0)
     * Assert\NotBlank()
     * Assert\Type(
     *      type = "numeric",
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
     * @ORM\OneToOne(
     *      targetEntity="David\TicketBundle\Entity\Guest",
     *      mappedBy="ticket",
     *      cascade={"persist"})
     * )
     * @Assert\Valid()
     */
    private $guest;

    /**
     * @ORM\ManyToOne(
     *      targetEntity="David\TicketBundle\Entity\Reservation",
     *      inversedBy="tickets",
     *      cascade={"persist", "remove"})
     * )
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
     * Set priceType
     *
     * @param string $priceType
     *
     * @return Ticket
     */
    public function setPriceType($priceType)
    {
        // $this->priceType = $priceType;

        $today = new \DateTime();
        $interval = $today->diff($priceType)->format('%Y');

        if ($interval < 18) {
            $this->priceType = 'minor';
        } else {
            $this->priceType = 'adult';
        }

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
