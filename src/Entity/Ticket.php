<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketRepository")
 */
class Ticket
{
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
}

