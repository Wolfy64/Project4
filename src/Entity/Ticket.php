<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketRepository")
 */
class Ticket
{
    const PRICE_ELDERLY = 12;
    const PRICE_NORMAL = 16;
    const PRICE_CHILD = 8;
    const PRICE_TODDLER = 0;
    const PRICE_DISCOUNT_RATE = 10;

    const ELDERLY = 'elderly';
    const NORMAL = 'normal';
    const CHILD = 'child';
    const TODDLER = 'toddler';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
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
     *      targetEntity="App\Entity\Guest",
     *      mappedBy="ticket",
     *      cascade={"persist"})
     * )
     * @Assert\Valid()
     */
    private $guest;

    /**
     * @ORM\ManyToOne(
     *      targetEntity="App\Entity\Reservation",
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
     * Get priceType
     *
     * @return string
     */
    public function getPriceType()
    {
        return $this->priceType;
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
     * Get reducedPrice
     *
     * @return boolean
     */
    public function getReducedPrice()
    {
        return $this->reducedPrice;
    }

    /**
     * Get guest
     *
     * @return App\Entity\Guest
     */
    public function getGuest()
    {
        return $this->guest;
    }

    /**
     * Get reservation
     *
     * @return App\Entity\Reservation
     */
    public function getReservation()
    {
        return $this->reservation;
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
     * Set guest
     *
     * @param App\Entity\Guest $guest
     *
     * @return Ticket
     */
    public function setGuest($guest = null)
    {
        $this->guest = $guest;

        $guest->setTicket($this);

        return $this;
    }

    /**
     * Set reservation
     *
     * @param App\Entity\Reservation $reservation
     *
     * @return Ticket
     */
    public function setReservation($reservation = null)
    {
        $this->reservation = $reservation;

        return $this;
    }

    /**
     * Define priceType
     * 
     * @param DateTime $dateOfBirth
     * 
     * @return priceType
     */
    public function doPriceType()
    {
        $today = new \DateTime();
        $interval = $today->diff($this->guest->getDateOfBirth())->format('%Y');

        switch ($interval) {
            case $interval >= 60:
                $this->priceType = self::ELDERLY;
                break;
            case $interval >= 12:
                $this->priceType = self::NORMAL;
                break;
            case $interval > 4 && $interval < 12:
                $this->priceType = self::CHILD;
                break;
            case $interval <= 4:
                $this->priceType = self::TODDLER;
                break;
            default:
                throw new Exception("Error Processing Request => priceType: " . $interval);
                break;
        }
    }

    /**
     * Define amount
     * 
     * @param const priceType
     * 
     * @return amount
     */
    public function doAmount()
    {
        switch ($this->priceType) {
            case self::ELDERLY:
                $this->amount = self::PRICE_ELDERLY;
                break;
            case self::NORMAL:
                $this->amount = self::PRICE_NORMAL;
                break;
            case self::CHILD:
                $this->amount = self::PRICE_CHILD;
                break;
            case self::TODDLER:
                $this->amount = self::PRICE_TODDLER;
                break;
            default:
                throw new Exception("Error Processing Request => amount: " . $this->priceType);
                break;
        }

        // Subtract discount if priceType = 'normal'
        if ($this->getReducedPrice() === true && $this->priceType === self::NORMAL) {
            $this->amount -= self::PRICE_DISCOUNT_RATE;
        }
    }
}

