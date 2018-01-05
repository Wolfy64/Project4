<?php

namespace App\Services;

use App\Entity\Reservation;

class Compute
{
    const PRICE_MINIMUM = 0;

    const PRICE_ELDERLY = 12;
    const PRICE_NORMAL  = 16;
    const PRICE_CHILD   = 8;
    const PRICE_TODDLER = 0;
    const PRICE_DISCOUNT_RATE = 10;

    const ELDERLY = 'elderly';
    const NORMAL  = 'normal';
    const CHILD   = 'child';
    const TODDLER = 'toddler';

    public function price($reservation)
    {
        // Define $priceType $Amount and add Ticket in Reservation
        foreach ($reservation->getTickets() as $ticket) {
            $this->doPriceType($ticket);
            $this->doAmount($ticket);
            $ticket->setReservation($reservation);
        }

        // Define cost for the whole reservation
        $this->doCost($reservation);

    }

    /**
     * Define priceType
     * 
     * @return priceType
     */
    public function doPriceType($ticket)
    {
        $today = new \DateTime();
        $guest = $ticket->getGuest();
        $interval = $today->diff($guest->getDateOfBirth())->format('%Y');

        switch ($interval) {
            case $interval >= 60:
                $priceType = self::ELDERLY;
                break;
            case $interval >= 12:
                $priceType = self::NORMAL;
                break;
            case $interval >= 4 && $interval < 12:
                $priceType = self::CHILD;
                break;
            case $interval < 4:
                $priceType = self::TODDLER;
                break;
            default:
                throw new Exception("Error Processing Request => priceType: " . $interval);
                break;
        }

        $ticket->setPriceType($priceType);
    }

    /**
     * Define amount
     * 
     * @return amount
     */
    public function doAmount($ticket)
    {
        switch ($ticket->getPriceType()) {
            case self::ELDERLY:
                $amount = self::PRICE_ELDERLY;
                break;
            case self::NORMAL:
                $amount = self::PRICE_NORMAL;
                break;
            case self::CHILD:
                $amount = self::PRICE_CHILD;
                break;
            case self::TODDLER:
                $amount = self::PRICE_TODDLER;
                break;
            default:
                throw new Exception("Error Processing Request => amount: " . $this->priceType);
                break;
        }

        // Subtract discount
        if ($ticket->getReducedPrice() === true && $ticket->getPriceType() === self::NORMAL) {
            $amount -= self::PRICE_DISCOUNT_RATE;
        }

        $ticket->setAmount($amount);
    }

    /**
     * Define price of $cost
     * 
     * @return $cost
     */
    public function doCost($reservation)
    {
        foreach ($reservation->getTickets() as $ticket) {
            $cost = $reservation->getCost() + $ticket->getAmount();
            $reservation->setCost($cost);
        }
    }

}