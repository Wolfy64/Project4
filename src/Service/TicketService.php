<?php

namespace App\Service;

use App\Entity\Ticket;
use Symfony\Component\Config\Definition\Exception\Exception;

class TicketService
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

    private $ticket;

    public function setTicket($ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Define priceType
     * 
     * @return priceType
     */
    public function doPriceType()
    {
        $dateOfBirth = $this->ticket->getGuest()->getDateOfBirth();
        $today = new \DateTime();
        $interval = $today->diff($dateOfBirth)->format('%Y');

        switch ($interval) {
            case $interval >= 60:
                return self::ELDERLY;
                break;
            case $interval >= 12:
                return self::NORMAL;
                break;
            case $interval > 4 && $interval < 12:
                return self::CHILD;
                break;
            case $interval <= 4:
                return self::TODDLER;
                break;
            default:
                throw new Exception("Error Processing Request => priceType: " . $interval);
                break;
        }
    }

    /**
     * Define amount
     * 
     * @return amount
     */
    public function doAmount()
    {
        $priceType = $this->ticket->getPriceType();
        $amout = $this->ticket->getAmount();
        $reducedPriced = $this->ticket->getReducedPrice();
        \dump($priceType);

        switch ($priceType) {
            case self::ELDERLY:
                return self::PRICE_ELDERLY;
                break;
            case self::NORMAL:
                return self::PRICE_NORMAL;
                break;
            case self::CHILD:
                return self::PRICE_CHILD;
                break;
            case self::TODDLER:
                return self::PRICE_TODDLER;
                break;
            default:
                throw new Exception("Error Processing Request => amount: " . $priceType);
                break;
        }

        // Subtract discount if priceType = 'normal'
        if ($reducedPriced === true && $priceType === self::NORMAL) {
            $amount -= self::PRICE_DISCOUNT_RATE;
        }
    }
}
