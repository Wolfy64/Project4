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
    const ELDERLY = 12;
    const NORMAL = 16;
    const CHILD = 8;
    const TODDLER = 0;
    const DISCOUNT_RATE = 10;
    
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
     */
    private $priceType;

    /**
     * @var decimal
     * @ORM\Column(name="amount", type="decimal", precision=10, scale=0)
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
        $today = new \DateTime();
        $interval = $today->diff($priceType)->format('%Y');

        switch ($interval) {
            case $interval >= 60:
                $this->priceType = 'elderly';
                break;
            case $interval >= 12:
                $this->priceType = 'normal';
                break;
            case $interval > 4 && $interval < 12:
                $this->priceType = 'child';
                break;
            case $interval <= 4:
                $this->priceType = 'toddler';
                break;
            default:
                throw new Exception("Error Processing Request => priceType: " . $interval);
                break;
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
        switch ($amount) {
            case 'elderly':
                $this->amount = $this::ELDERLY;
                break;
            case 'normal':
                $this->amount = $this::NORMAL;
                break;
            case 'child':
                $this->amount = $this::CHILD;
                break;
            case 'toddler':
                $this->amount = $this::TODDLER;
                break;
            default:
                throw new Exception("Error Processing Request => amount: ".$amount);
                break;
        }

        // Subtract discount if priceType = 'normal'
        if ( $this->getReducedPrice() === true && $this->priceType === 'normal'){
            $this->amount = $this->amount - $this::DISCOUNT_RATE;
        }

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
    public function setGuest($guest = null)
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
    public function setReservation($reservation = null)
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
