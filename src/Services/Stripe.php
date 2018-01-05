<?php

namespace App\Services;

class Stripe
{
    const SECRET_KEY = "sk_test_vR13pPT8iogxBJKWC1FOuDDj";
    const PUBLISHABLE_KEY = "pk_test_zwG6fcavFG9NgdGA3aOaY2oZ";

    private $reservation;
    private $errorMsg;

    public function setReservation($reservation)
    {
        $this->reservation = $reservation;
    }

    public function process($email, $token)
    {
        \Stripe\Stripe::setApiKey(self::SECRET_KEY);

        $customer = \Stripe\Customer::create([
            'email'  => $email,
            'source' => $token
        ]);

        try {
            \Stripe\Charge::create([
                'customer'    => $customer->id,
                'amount'      => $this->reservation->getCost() * 100,
                'currency'    => 'eur',
                'description' => 'Louvre',
            ]);
            return true;
        } catch (\Stripe\Error\Card $e) {
            // Since it's a decline, \Stripe\Error\Card will be caught
            $body = $e->getJsonBody();
            $this->errorMsg = $body['error']['message'];
            return false;
        } catch (\Stripe\Error\RateLimit $e) {
            // Too many requests made to the API too quickly
            $body = $e->getJsonBody();
            $this->errorMsg = $body['error']['message'];
            return false;
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
            $body = $e->getJsonBody();
            $this->errorMsg = $body['error']['message'];
            return false;
        } 
    }

    public function getErrorMsg()
    {
        return 'ERROR : ' . $this->errorMsg;
    }
}